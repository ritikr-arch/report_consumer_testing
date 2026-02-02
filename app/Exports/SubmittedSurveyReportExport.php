<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Survey;
use App\Models\Market;
use App\Models\SubmittedSurvey;

class SubmittedSurveyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings, WithEvents
{
    protected $filters;
    protected $submittedSurveys; 
    protected $markets;
    private $minPrice;
    private $maxPrice;
    private $surveyName;
    private $zoneName;

    public function __construct(array $filters = []){
        $this->filters = $filters;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->surveyName = null;
        $this->zoneName = null;
    }


    public function styles(Worksheet $sheet){
        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowPrices = [];
            $priceCells = [];

            foreach (range('D', 'Z') as $column) {
                $cellCoordinate = "$column$row";
                $value = $sheet->getCell($cellCoordinate)->getValue();

                if (is_numeric($value)) {
                    $floatValue = (float) $value;
                    $rowPrices[] = $floatValue;
                    $priceCells[$cellCoordinate] = $floatValue;
                }
            }

            if(!empty($rowPrices)){
                $minPrice = min($rowPrices);
                $maxPrice = max($rowPrices);

                foreach ($priceCells as $cell => $value) {
                    if ($value == $minPrice && $minPrice !== $maxPrice) {
                        $sheet->getStyle($cell)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FF228522'], // Green
                            ],
                            'font' => ['color' => ['argb' => 'FFFFFFFF']],
                        ]);
                    } elseif ($value == $maxPrice && $minPrice !== $maxPrice) {
                        $sheet->getStyle($cell)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFE52929'], // Red
                            ],
                            'font' => ['color' => ['argb' => 'FFFFFFFF']],
                        ]);
                    }
                }
            }
        }

        for ($row = 1; $row <= $highestRow; $row++) {
            $firstCell = $sheet->getCell("A$row")->getValue();
            if ($firstCell && !is_numeric($firstCell)) {
                $sheet->getStyle("A$row:Z$row")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }
        }
    }

    public function collection(){
        $id = $this->filters['id'] ?? null;

        if (!$id) {
            return collect([]); 
        }

        $this->submittedSurveys = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'brand', 'unit'])
            ->where(['survey_id' => $id, /*'is_submit' => 1*/]);

        if (!empty($this->filters['commodity'])) {
            $this->submittedSurveys->where('commodity_id', $this->filters['commodity']);
        }
        if (!empty($this->filters['market'])) {
            $this->submittedSurveys->where('market_id', $this->filters['market']);
        }
        if (!empty($this->filters['category'])) {
            $this->submittedSurveys->where('category_id', $this->filters['category']);
        }
        if (!empty($this->filters['commodity_expiry_date'])) {
            $this->submittedSurveys->whereDate('commodity_expiry_date', $this->filters['commodity_expiry_date']);
        }

        if (!empty($this->filters['start_date'])) {
            $this->submittedSurveys->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $this->submittedSurveys->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        $this->submittedSurveys = $this->submittedSurveys->get();

        $this->markets = Market::where('status', '1')
            ->whereIn('id', $this->submittedSurveys->pluck('market_id')->unique())
            ->orderBy('name', 'asc')
            ->get();

        return $this->submittedSurveys;
    }

    public function drawings(){
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('admin/images/excel-logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('E1'); 
        $drawing->setOffsetX(50); 

        return [$drawing];
    }

    public function headings(): array{   
        $id = $this->filters['id'] ?? null;

        $surveyDetails = Survey::with('zone')->find($id);
        $zoneName = ($surveyDetails->zone)?$surveyDetails->zone->name:'Test ZONE';
        $surveyName = $surveyDetails->name??'TEST SERVER';

        $headings = [
            [' '], 
            [' '], 
            [' '], 
            [' '], 
            [' '], 
            ['Consumer Affairs Department Saint Kitts & Nevis'], 
            ['A LOOK AT THE SUPERMARKET PRICES'],               
            ['Collected in St. Kitts on '. date('d-m-Y', strtotime($surveyDetails->start_date))],            
            ['Survey Name: ' . strtoupper($surveyName)],        
            ['Zone Name: ' . strtoupper($zoneName)],            
        ];

        if(!$id){
            $headings[] = ['Commodities', 'Brand', 'Unit'];
            return $headings;
        }

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');

        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds) 
            ->orderBy('name', 'asc')
            ->pluck('name')
            ->map(function ($name) {
                return ucfirst($name). '(in $)'; // Add $ before name
            })
            ->toArray();

        $headings[] = array_merge(['Commodities', 'Brand', 'Unit'], array_map('ucfirst', $markets));

        return $headings;
    }


    public function mergedCells(): array{
        return [
            'A1:D1',
            'A2:D2',
        ];
    }

    // public function registerEvents(): array{
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $highestRow = $sheet->getHighestRow();

    //             $marketCount = count($this->markets ?? []);

    //             for ($row = 1; $row <= $highestRow; $row++) {
    //                 $cellValue = $sheet->getCell("A{$row}")->getValue();

    //                 if (strpos($cellValue, '__CATEGORY__') === 0) {
    //                     $categoryName = str_replace('__CATEGORY__', '', $cellValue);

    //                     $mergeToColumnIndex = 3 + $marketCount;
    //                     $mergeTo = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeToColumnIndex);

    //                     $sheet->mergeCells("A{$row}:{$mergeTo}{$row}");
    //                     $sheet->setCellValue("A{$row}", $categoryName);

    //                     $sheet->getStyle("A{$row}")->applyFromArray([
    //                         'font' => ['bold' => true],
    //                         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    //                         'fill' => [
    //                             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                             'startColor' => ['rgb' => 'D9E1F2'],
    //                         ],
    //                     ]);
    //                 }
    //             }
    //         }
    //     ];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $marketCount = count($this->markets ?? []);

                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("A{$row}")->getValue();

                    if (strpos($cellValue, '__CATEGORY__') === 0) {
                        // Format category row
                        $categoryName = str_replace('__CATEGORY__', '', $cellValue);

                        $mergeToColumnIndex = 3 + $marketCount;
                        $mergeTo = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mergeToColumnIndex);

                        $sheet->mergeCells("A{$row}:{$mergeTo}{$row}");
                        $sheet->setCellValue("A{$row}", $categoryName);

                        $sheet->getStyle("A{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D9E1F2'],
                            ],
                        ]);
                    } else {
                        // Apply right alignment to amount cells (columns D onwards)
                        $startColIndex = 4; // D
                        $endColIndex = 3 + $marketCount;

                        for ($colIndex = $startColIndex; $colIndex <= $endColIndex; $colIndex++) {
                            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                            $sheet->getStyle("{$colLetter}{$row}")
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                        }
                    }
                }
            },
        ];
    }




    public function map($commodity): array{
        static $previousCategory = null; 

        $categoryName = $commodity->category->name ?? 'N/A';

        $commodityData = $this->submittedSurveys->where('commodity_id', $commodity->commodity_id);

        $groupedMarketData = $commodityData->groupBy('market_id')->map->first();

        static $processedCommodities = [];
        if (in_array($commodity->commodity_id, $processedCommodities)) {
            return []; 
        }
        $processedCommodities[] = $commodity->commodity_id;

        $uniquePrices = $groupedMarketData->pluck('amount')->filter()->unique()->values();
        $minPrice = $uniquePrices->min();
        $maxPrice = $uniquePrices->max();
        $isSamePrice = $uniquePrices->count() === 1;
        $onlyOnePriceExists = $commodityData->count() === 1 || $uniquePrices->count() === 1;

        $marketPrices = $this->markets->map(function ($market) use ($groupedMarketData, $minPrice, $maxPrice, $isSamePrice, $onlyOnePriceExists) {
            $marketPrice = $groupedMarketData->get($market->id);

            if ($marketPrice) {
                $amount = $marketPrice->amount;
                return $amount;
            }
            return '-';
        })->toArray();

        if ($previousCategory !== $categoryName) {
            $previousCategory = $categoryName;

            $categoryRow = strtoupper($categoryName);
            return [
                ['__CATEGORY__' . $categoryRow, ...array_fill(0, count($this->markets) + 2, '')],
                array_merge([
                    ucfirst($commodity->commodity->name ?? 'N/A'),
                    ucfirst($commodity->brand->name ?? 'N/A'),
                    ucfirst($commodity->unit->name ?? 'N/A'),
                ], $marketPrices)
            ];
        }

        return [
            array_merge([
                ucfirst($commodity->commodity->name ?? 'N/A'),
                ucfirst($commodity->brand->name ?? 'N/A'),
                ucfirst($commodity->unit->name ?? 'N/A'),
            ], $marketPrices)
        ];
    }




    

}