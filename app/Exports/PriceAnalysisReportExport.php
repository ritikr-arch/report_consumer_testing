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
use App\Models\Zone;
use App\Models\SubmittedSurvey;
use App\Models\SurveyCategory;
use App\Models\Commodity;
use App\Models\SurveyMarket;
use App\Models\Category;

class PriceAnalysisReportExport implements FromCollection, WithHeadings, WithMapping
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


    public function collection()
    {
        $id = $this->filters['id'] ?? null;
        if (!$id) {
            return collect([]);
        }

        $submittedSurveys = SubmittedSurvey::with([
            'commodity', 'brand', 'unit', 'market'
        ])
            ->where('survey_id', $id)
            ->get();

        $totalMarkets = SurveyMarket::where('survey_id', $id)->count();

        // Group by category -> commodity -> brand -> unit
        $groupedData = $submittedSurveys->groupBy(function ($item) {
            return optional($item->category)->name ?? 'Uncategorized';
        });

        $flatCollection = collect();

        foreach ($groupedData as $categoryName => $items) {
            $groupedByCombination = $items->groupBy(function ($item) {
                return $item->commodity_id . '-' . $item->brand_id . '-' . $item->unit_id;
            });

            foreach ($groupedByCombination as $group) {
                $first = $group->first();

                $commodityName = optional($first->commodity)->name ?? 'N/A';
                $brandName = optional($first->brand)->name ?? 'No Name';
                $unitName = optional($first->unit)->name ?? 'No Name';

                $prices = $group->pluck('amount')->sort()->values();
                $max = $prices->max();
                $min = $prices->min();
                $avg = $prices->avg();

                $count = $prices->count();
                $median = null;
                if ($count > 0) {
                    $middle = floor(($count - 1) / 2);
                    $median = ($count % 2)
                        ? $prices[$middle]
                        : ($prices[$middle] + $prices[$middle + 1]) / 2;
                }

                $uniqueMarketCount = $group->pluck('market_id')->unique()->count();
                $availability = ($totalMarkets > 0) ? ($uniqueMarketCount / $totalMarkets) * 100 : 0;

                $flatCollection->push([
                    'Commodity'     => $commodityName,
                    'Brand'         => $brandName,
                    'Unit'          => $unitName,
                    'Maximum Price' => $max,
                    'Minimum Price' => $min,
                    'Median Price'  => round($median, 2),
                    'Average Price' => round($avg, 2),
                    'Availability'  => round($availability, 2) . '%',
                ]);
            }
        }

        return $flatCollection;
    }


    public function headings(): array
    {
        return [
            'Commodity',
            'Brand',
            'Unit',
            'Maximum Price',
            'Minimum Price',
            'Median Price',
            'Average Price',
            'Availability',
        ];
    }

    public function map($row): array
    {
        return [
            strtoupper($row['category']),
            $row['commodity'],
            $row['brand'],
            $row['unit'],
            '$' . number_format($row['max_price'], 2),
            '$' . number_format($row['min_price'], 2),
            '$' . number_format($row['median_price'], 2),
            '$' . number_format($row['avg_price'], 2),
            number_format($row['availability'], 2) . '%',
        ];
    }





    

}