<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Survey;

class ExcelTransformController extends Controller
{
    public function index(){
        $surveys = Survey::with('zone')->select('id', 'name', 'zone_id')->orderBy('name')->get();
        $zones = DB::table('zones')->select('id', 'name')->orderBy('name')->get();
        return view('admin.excel.upload', compact('surveys', 'zones'));
    }

    public function upload(Request $request){
        $request->validate([
            'survey_name' => 'required',
            'survey_id' => 'required|integer',
            'zone_name' => 'required',
            'zone_id' => 'required|integer',
            'input_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('input_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);
        $headerRow = $data[1];

        $fixedColumns = ['A', 'B', 'C'];
        $marketColumns = array_diff(array_keys($headerRow), $fixedColumns);

        $newSpreadsheet = new Spreadsheet();
        $newSheet = $newSpreadsheet->getActiveSheet();
        $newSheet->fromArray([
            'Survey Name', 'survey_id',
            'Zone Name', 'zone_id',
            'Market Name', 'market_id',
            'Category Name', 'category_id',
            'Commodity Name', 'commodity_id',
            'Unit Name', 'unit_id',
            'Brand Name', 'brand_id',
            'amount',
            'submitted_by',
            'commodity_expiry_date'
        ], NULL, 'A1');

        $newRowIndex = 2;
        $currentCategory = '';

        foreach ($data as $index => $row) {
            if ($index == 1) continue;

            $colAOriginal = trim($row['A'] ?? '');
            $colBOriginal = trim($row['B'] ?? '');
            $colCOriginal = trim($row['C'] ?? '');

            $colA = $this->normalize($colAOriginal);
            $colB = $this->normalize($colBOriginal);
            $colC = $this->normalize($colCOriginal);

            if ($colA !== '' && $colB === '' && $colC === '') {
                $currentCategory = $colAOriginal;
                continue;
            }

            if ($colA !== '') {
                $categoryId = $this->getIdByName('categories', 'name', $currentCategory);
                if (!$categoryId) continue;

                $brandId = $this->getBrandId($colBOriginal, $categoryId);
                if (!$brandId) continue;

                $uomId = $this->getUomId($colCOriginal, $categoryId, $brandId);
                if (!$uomId) continue;

                $commodityId = $this->getCommodityId($colAOriginal, $categoryId, $brandId, $uomId);
                if (!$commodityId) continue;

                foreach ($marketColumns as $col) {
                    $rawPrice = trim($row[$col] ?? '');
                    $cleanPrice = str_replace('$', '', $rawPrice);
                    $price = ($cleanPrice === '-' || $cleanPrice === '') ? '' : $cleanPrice;

                    $marketName = $headerRow[$col] ?? 'Unknown Market';
                    $marketId = $this->getIdByName('markets', 'name', $marketName);

                    $newSheet->fromArray([
                        $request->survey_name,
                        $request->survey_id,
                        $request->zone_name,
                        $request->zone_id,
                        $marketName,
                        $marketId,
                        $currentCategory,
                        $categoryId,
                        $colAOriginal,
                        $commodityId,
                        $colCOriginal,
                        $uomId,
                        $colBOriginal,
                        $brandId,
                        $price,
                        54,
                        now()->toDateString()
                    ], NULL, 'A' . $newRowIndex++);
                }
            }
        }

        $response = new StreamedResponse(function () use ($newSpreadsheet) {
            $writer = new Xlsx($newSpreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="transformed_data.xlsx"');
        return $response;
    }

    // Normalize helper
    private function normalize(string $value): string {
        return strtolower(str_replace(' ', '', trim($value)));
    }

    private function getIdByName(string $table, string $column, string $value): ?int {
        $normalized = $this->normalize($value);
        return DB::table($table)
            ->whereRaw("REPLACE(LOWER($column), ' ', '') = ?", [$normalized])
            ->value('id');
    }

    private function getBrandId(string $brand, int $categoryId): ?int {
        $normalized = $this->normalize($brand);
        return DB::table('brands')
            ->whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalized])
            ->where('category_id', $categoryId)
            ->value('id');
    }

    private function getUomId(string $uom, int $categoryId, int $brandId): ?int {
        $normalized = $this->normalize($uom);
        return DB::table('uom')
            ->whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalized])
            ->where('categories_id', $categoryId)
            ->where('brand_id', $brandId)
            ->value('id');
    }

    private function getCommodityId(string $name, int $categoryId, int $brandId, int $uomId): ?int {
        $normalized = $this->normalize($name);
        return DB::table('commodities')
            ->whereRaw("REPLACE(LOWER(name), ' ', '') = ?", [$normalized])
            ->where('category_id', $categoryId)
            ->where('brand_id', $brandId)
            ->where('uom_id', $uomId)
            ->value('id');
    }
}
