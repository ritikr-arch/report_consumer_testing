<?php

namespace App\Exports;

use App\Models\SubmittedSurvey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class SurveillanceReportExport implements FromView
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $startDate = $this->filters['start_date'] ?? null;
        $endDate   = $this->filters['end_date'] ?? null;
        $typeId    = $this->filters['type_id'] ?? null;

        $submittedSurveys = SubmittedSurvey::with([
            'commodity:id,name',
            'category:id,name',
            'market:id,name',
            'zone:id,name',
            'brand:id,name',
            'unit:id,name',
            'survey:id,type_id'
        ])
        ->when($typeId, function ($query) use ($typeId) {
            $query->whereHas('survey', function ($q) use ($typeId) {
                $q->where('type_id', $typeId);
            });
        })
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        })
        ->whereNotNull('brand_id')
        ->whereNotNull('unit_id')
        ->get();

        $grouped = $submittedSurveys
            ->filter(fn($item) => $item->category && $item->commodity)
            ->groupBy(fn($item) => $item->category->name)
            ->map(function ($categoryItems) {
                return $categoryItems->groupBy(fn($item) => $item->commodity->id)->map(function ($commodityItems) {
                    $amounts = $commodityItems->pluck('amount')->filter()->sort()->values()->toArray();
                    $count = count($amounts);

                    $median = $count
                        ? ($count % 2
                            ? $amounts[floor($count / 2)]
                            : ($amounts[$count / 2 - 1] + $amounts[$count / 2]) / 2)
                        : 0;

                    $max = $count ? max($amounts) : 0;
                    $min = $count ? min($amounts) : 0;
                    $avg = $count ? array_sum($amounts) / $count : 0;

                    $marketCount = $commodityItems->pluck('market_id')->unique()->count();
                    $totalMarkets = $commodityItems->pluck('market_id')->count();
                    $availability = ($totalMarkets > 0) ? (($marketCount / $totalMarkets) * 100) : 0;

                    $firstItem = $commodityItems->first();

                    return [
                        'commodityName' => optional($firstItem->commodity)->name ?? 'N/A',
                        'brandName'     => optional($firstItem->brand)->name ?? 'No Name',
                        'unitName'      => optional($firstItem->unit)->name ?? 'No Unit',
                        'max'           => $max,
                        'min'           => $min,
                        'median'        => $median,
                        'avg'           => $avg,
                        'availability'  => $availability,
                    ];
                });
            });

        return view('admin.report.surveillance-report-export', [
            'grouped'  => $grouped,
            'typeId'   => $typeId,
            'startDate'=> $startDate,
            'endDate'  => $endDate,
        ]);
    }
}
