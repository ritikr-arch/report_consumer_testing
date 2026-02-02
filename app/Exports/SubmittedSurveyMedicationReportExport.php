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

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Survey;
use App\Models\Market;
use App\Models\SubmittedSurvey;


    use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SubmittedSurveyMedicationReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithDrawings
{
    protected $filters;
    protected $submittedSurveys; 
    protected $markets;
    private $minPrice;
    private $maxPrice;
    private $surveyName;
    private $zoneName;
    private $categoryHeadingRows = [];
    private $currentRow = 8;

    public function __construct(array $filters = []){
        $this->filters = $filters;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->surveyName = null;
        $this->zoneName = null;
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

    // public function headings(): array
    // {
    //     $id = $this->filters['id'] ?? null;

    //     $surveyDetails = Survey::with('zone')->find($id);
    //     $zoneName = ($surveyDetails->zone) ? $surveyDetails->zone->name : 'Test ZONE';
    //     $surveyName = $surveyDetails->name ?? 'TEST SERVER';

    //     $headings = [
    //         ['Consumer Affairs Department'],
    //         ['Essential Medication List'],
    //         ['Period: July 2025'],
    //         [],
    //     ];

    //     if ($id) {
    //         $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');

    //         $markets = Market::where('status', '1')
    //             ->whereIn('id', $marketIds)
    //             ->orderBy('name', 'asc')
    //             ->get();

    //         $marketRow = ['Pharmacies'];
    //         foreach ($markets as $market) {
    //             $marketRow[] = $market->name;
    //             $marketRow[] = '';
    //         }
    //         $headings[] = $marketRow;

    //         $addressRow = ['Address (Location)'];
    //         foreach ($markets as $market) {
    //             $addressRow[] = $market->address ?? '';
    //             $addressRow[] = '';
    //         }
    //         $headings[] = $addressRow;

    //         $categoryRow = ['Commodities'];
    //         foreach ($markets as $market) {
    //             $categoryRow[] = 'Generic';
    //             $categoryRow[] = 'Original';
    //         }
    //         $headings[] = $categoryRow;

    //     }else{
    //         $headings[] = ['Commodities', 'Brand', 'Unit'];
    //     }

    //     return $headings;
    // }

    public function drawings(){
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('admin/images/excel-logo.png'));
        $drawing->setHeight(70);
        $drawing->setCoordinates('B1'); 
        $drawing->setOffsetX(50); 

        return [$drawing];
    }

  public function headings(): array
{
    $id = $this->filters['id'] ?? null;

    $surveyDetails = Survey::with('zone')->find($id);
    $zoneName = ($surveyDetails->zone) ? $surveyDetails->zone->name : 'Test ZONE';
    $surveyName = $surveyDetails->name ?? 'TEST SERVER';

    $headings = [
        ['Consumer Affairs Department'],
        [ucwords($surveyName)],
        ['Period: ' . date('M Y', strtotime($surveyDetails->start_date))],
        [], // spacer row
    ];

    if ($id) {
        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');

        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds)
            ->orderBy('name', 'asc')
            ->get();

        // Row 5: Pharmacies (Market Names)
        $marketRow = ['Pharmacies','',''];
        foreach ($markets as $market) {
            $marketRow[] = $market->name;
            $marketRow[] = ''; // Empty cell to cover next column
        }
        $headings[] = $marketRow;

        // Row 6: Addresses
        $addressRow = ['Address (Location)','',''];
        foreach ($markets as $market) {
            $addressRow[] = $market->address ?? '';
            $addressRow[] = ''; // match colspan
        }
        $headings[] = $addressRow;

        // Row 7: Column Labels (Generic / Original)
        $categoryRow = ['Commodities','Content'];
        foreach ($markets as $market) {
            $categoryRow[] = 'Generic($)';
            $categoryRow[] = 'Original($)';
        }
        $headings[] = $categoryRow;

    } else {
        // fallback
        $headings[] = ['Commodities'];
    }

    return $headings;
}


    // public function headings(): array
    // {
    //     $id = $this->filters['id'] ?? null;

    //     $surveyDetails = Survey::with('zone')->find($id);
    //     $zoneName = ($surveyDetails->zone) ? $surveyDetails->zone->name : 'Test ZONE';
    //     $surveyName = $surveyDetails->name ?? 'TEST SERVER';

    //     $headings = [
    //         ['Consumer Affairs Department'],
    //         [ucwords($surveyName)],
    //         ['Period: '. date('M Y', strtotime($surveyDetails->start_date))],
    //         [],
    //     ];

    //     if ($id) {
    //         $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');

    //         $markets = Market::where('status', '1')
    //             ->whereIn('id', $marketIds)
    //             ->orderBy('name', 'asc')
    //             ->get();

    //         // Row 5: Pharmacies
    //         $marketRow = ['Pharmacies', ''];
    //         foreach ($markets as $market) {
    //             $marketRow[] = $market->name;
    //            // $marketRow[] = ''; // For merge
    //         }
    //         $headings[] = $marketRow;

    //         // Row 6: Addresses
    //     $addressRow = ['Address (Location)', '']; // <- Add extra ''
    //         foreach ($markets as $market) {
    //             $addressRow[] = $market->address ?? '';
    //             //$addressRow[] = ''; // For merge
    //         }
    //         $headings[] = $addressRow;

    //         // Row 7: Commodity types
    //         $categoryRow = ['Commodities','Content'];
    //        // $contentRow = ['Content'];
    //         //$headings[] = $contentRow;
    //         foreach ($markets as $market) {
    //             $categoryRow[] = 'Generic($)';
    //             $categoryRow[] = 'Original($)';
    //         }
    //         $headings[] = $categoryRow;

    //     } else {
    //         $headings[] = ['Commodities',''];
    //     }

    //     return $headings;
    // }

    public function mergedCells(): array{
        return [
            'A1:D1',
            'A2:D2',
            'A3:D3',
            'B7:C7',
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $highestColumn = $sheet->getHighestColumn();

    //             // Merge pharmacy and address cells
    //             $markets = $this->markets ?? [];
    //             $startColumn = 2; // Starting from column B (A = index 1)

    //             foreach ($markets as $index => $market) {
    //                 $colIndex = $startColumn + ($index * 2);
    //                 $colLetter1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
    //                 $colLetter2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);

    //                 // Row 5: Pharmacy names
    //                 $sheet->mergeCells("{$colLetter1}5:{$colLetter2}5");

    //                 // Row 6: Address
    //                 $sheet->mergeCells("{$colLetter1}6:{$colLetter2}6");
    //             }

    //             // Style rows 1-3 with smaller height and no wrap
    //             for ($row = 1; $row <= 3; $row++) {
    //                 $cellRange = "A{$row}:{$highestColumn}{$row}";
    //                 $sheet->getRowDimension($row)->setRowHeight(18); // Set fixed small height
    //                 $sheet->getStyle($cellRange)->applyFromArray([
    //                     'font' => ['bold' => true],
    //                     'alignment' => [
    //                         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
    //                         'wrapText' => false,
    //                     ],
    //                 ]);
    //             }

    //             // Style rows 4–7 with wrap and thicker borders
    //             for ($row = 4; $row <= 7; $row++) {
    //                 $cellRange = "A{$row}:{$highestColumn}{$row}";
    //                 $sheet->getStyle($cellRange)->applyFromArray([
    //                     'font' => ['bold' => true],
    //                     'borders' => [
    //                         'allBorders' => [
    //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                             'color' => ['argb' => '000000'],
    //                         ],
    //                     ],
    //                     'alignment' => [
    //                         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                         'wrapText' => false,
    //                     ],
    //                 ]);
    //             }

    //             foreach ($this->categoryHeadingRows as $row) {
    //                 $highestColumn = $sheet->getHighestColumn();
    //                 $sheet->mergeCells("A{$row}:{$highestColumn}{$row}");

    //                 // Optional styling for category rows
    //                 $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
    //                     'font' => ['bold' => true],
    //                     'alignment' => [
    //                         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    //                         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                     ],
    //                 ]);
    //             }

    //         },
    //     ];
    // }


// public function registerEvents(): array
// {
//     return [
//         AfterSheet::class => function (AfterSheet $event) {
//             $sheet = $event->sheet->getDelegate();

//             // Ensure $this->markets is set
//             $markets = $this->markets ?? [];
//             $marketCount = count($markets);

//             $startColumnIndex = 2; // B = column 2 (A = 1)
//             foreach ($markets as $index => $market) {
//                 $colIndex1 = $startColumnIndex + ($index * 2);
//                 $colIndex2 = $colIndex1 + 1;

//                 $colLetter1 = Coordinate::stringFromColumnIndex($colIndex1);
//                 $colLetter2 = Coordinate::stringFromColumnIndex($colIndex2);

//                 // Merge cells for Market Name (Row 5) and Address (Row 6)
//                 $sheet->mergeCells("{$colLetter1}5:{$colLetter2}5");
//                 $sheet->mergeCells("{$colLetter1}6:{$colLetter2}6");
//             }

//             // Styling: Rows 1–3 (small height, bold, centered)
//             for ($row = 1; $row <= 3; $row++) {
//                 $sheet->getRowDimension($row)->setRowHeight(18);
//                 $sheet->getStyle("A{$row}")->getFont()->setBold(true);
//                 $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//                 $sheet->getStyle("A{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
//             }

//             // Styling: Rows 4–7 (wrap, borders, bold, centered)
//             $totalColumns = ($marketCount * 2) + 2; // 1st two columns: Commodity, Content
//             $lastColumnLetter = Coordinate::stringFromColumnIndex($totalColumns);

//             for ($row = 4; $row <= 7; $row++) {
//                 $cellRange = "A{$row}:{$lastColumnLetter}{$row}";
//                 $sheet->getStyle($cellRange)->applyFromArray([
//                     'font' => ['bold' => true],
//                     'borders' => [
//                         'allBorders' => [
//                             'borderStyle' => Border::BORDER_THIN,
//                             'color' => ['argb' => '000000'],
//                         ],
//                     ],
//                     'alignment' => [
//                         'horizontal' => Alignment::HORIZONTAL_CENTER,
//                         'vertical' => Alignment::VERTICAL_CENTER,
//                         'wrapText' => true,
//                     ],
//                 ]);
//             }

//             // Optional: Merge category headings if $this->categoryHeadingRows is set
//             if (!empty($this->categoryHeadingRows)) {
//                 foreach ($this->categoryHeadingRows as $row) {
//                     $cellRange = "A{$row}:{$lastColumnLetter}{$row}";
//                     $sheet->mergeCells($cellRange);
//                     $sheet->getStyle($cellRange)->applyFromArray([
//                         'font' => ['bold' => true],
//                         'alignment' => [
//                             'horizontal' => Alignment::HORIZONTAL_LEFT,
//                             'vertical' => Alignment::VERTICAL_CENTER,
//                         ],
//                     ]);
//                 }
//             }
//         },
//     ];
// }

public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            $markets = $this->markets ?? [];
            $marketCount = count($markets);

            $startColumnIndex = 3; // Column C
            foreach ($markets as $index => $market) {
                $colIndex1 = $startColumnIndex + ($index * 2);
                $colIndex2 = $colIndex1 + 1;

                $colLetter1 = Coordinate::stringFromColumnIndex($colIndex1);
                $colLetter2 = Coordinate::stringFromColumnIndex($colIndex2);

                // Merge for market name and address
                $sheet->mergeCells("{$colLetter1}5:{$colLetter2}5");
                $sheet->mergeCells("{$colLetter1}6:{$colLetter2}6");

                // Set values
                $sheet->setCellValue("{$colLetter1}5", $market['name'] ?? '');
                $sheet->setCellValue("{$colLetter1}6", $market['address'] ?? '');
                $sheet->setCellValue("{$colLetter1}7", 'Generic($)');
                $sheet->setCellValue("{$colLetter2}7", 'Original($)');
            }

            // Set default column width to 30 for all used columns
            $totalColumns = ($marketCount * 2) + 2; // +2 for Commodity & Content columns (A, B)
            for ($i = 1; $i <= $totalColumns; $i++) {
                $colLetter = Coordinate::stringFromColumnIndex($i);
                $sheet->getColumnDimension($colLetter)->setWidth(20);
            }

            // Set header text for first two columns
            $sheet->setCellValue('A7', 'Commodities');
            $sheet->setCellValue('B7', 'Content');

            // Style: Rows 1 to 3 (basic center, bold)
            for ($row = 1; $row <= 3; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(18);
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
            }

            // Style: Rows 4 to 7 (bold, center, border, wrap text)
            $lastColumnLetter = Coordinate::stringFromColumnIndex($totalColumns);
            for ($row = 4; $row <= 7; $row++) {
                $cellRange = "A{$row}:{$lastColumnLetter}{$row}";
                $sheet->getStyle($cellRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
            }

            // Optional category headings
            if (!empty($this->categoryHeadingRows)) {
                foreach ($this->categoryHeadingRows as $row) {
                    $cellRange = "A{$row}:{$lastColumnLetter}{$row}";
                    $sheet->mergeCells($cellRange);
                    $sheet->getStyle($cellRange)->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
            }
        },
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

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $highestRow = $sheet->getHighestRow();
    //             $highestColumn = $sheet->getHighestColumn();
    //             $marketCount = count($this->markets ?? []);

    //             // Style for heading rows (bold text + bold border)
    //             $headingRows = range(1, 7); // Adjust based on your headings

    //             foreach ($headingRows as $row) {
    //                 $cellRange = "A{$row}:{$highestColumn}{$row}";
    //                 $sheet->getStyle($cellRange)->applyFromArray([
    //                     'font' => ['bold' => true],
    //                     'borders' => [
    //                         'allBorders' => [
    //                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
    //                             'color' => ['argb' => '000000'],
    //                         ],
    //                     ],
    //                     'alignment' => [
    //                         'horizontal' => Alignment::HORIZONTAL_CENTER,
    //                         'vertical' => Alignment::VERTICAL_CENTER,
    //                     ],
    //                 ]);
    //             }

    //             // Special styling for category rows
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
    //                         'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    //                         'fill' => [
    //                             'fillType' => Fill::FILL_SOLID,
    //                             'startColor' => ['rgb' => 'D9E1F2'],
    //                         ],
    //                         'borders' => [
    //                             'allBorders' => [
    //                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
    //                                 'color' => ['argb' => '000000'],
    //                             ],
    //                         ],
    //                     ]);
    //                 }
    //             }
    //         },
    //     ];
    // }



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

        $marketPrices = $this->markets->flatMap(function ($market) use ($groupedMarketData) {
            $marketPrice = $groupedMarketData->get($market->id);

            if ($marketPrice) {
                $amount = $marketPrice->amount;
                $amount_1 = $marketPrice->amount_1;

                return [$amount ?? '-', $amount_1 ?? '-'];
            }

            return ['-', '-'];
        })->toArray();


        // if ($previousCategory !== $categoryName) {
        //     $previousCategory = $categoryName;

        //     $categoryRow = strtoupper($categoryName);
        //     return [
        //         [$categoryRow, ...array_fill(0, count($this->markets) + 2, '')],
        //         array_merge([
        //             ucfirst($commodity->commodity->name ?? 'N/A'),
        //             // ucfirst($commodity->brand->name ?? 'N/A'),
        //             // ucfirst($commodity->unit->name ?? 'N/A'),
        //         ], $marketPrices)
        //     ];
        // }
        if ($previousCategory !== $categoryName) {
            $previousCategory = $categoryName;

            $categoryRow = strtoupper($categoryName);
            $this->categoryHeadingRows[] = $this->currentRow; // Store the row to be merged
            $this->currentRow++; // Category row
            $this->currentRow++; // Data row

            return [
                [$categoryRow, ...array_fill(0, (count($this->markets) * 2), '')],
                array_merge([
                    ucfirst($commodity->commodity->name ?? 'N/A'),
                    ucfirst($commodity->unit->name ?? 'N/A'),
                ], $marketPrices)
            ];
        }
        $this->currentRow++; // For data row


        return [
            array_merge([
                ucfirst($commodity->commodity->name ?? 'N/A'),
                ucfirst($commodity->unit->name ?? 'N/A'),
            ], $marketPrices)
        ];
    }
    

}

