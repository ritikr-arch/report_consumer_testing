<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use App\Models\{
    User, Zone, Type, Survey, Market,
    Category, Commodity, SurveyMarket,
    SurveyCategory, SubmittedSurvey
};
use App\Exports\SurveillanceReportExport;

class SurveillanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:submit_survey_list', ['only' => ['index']]);
        $this->middleware('permission:submit_survey_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:submit_survey_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:submit_survey_delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
{
    $typeId     = $request->input('type_id');
    $selectedYear = $request->input('year');
    $selectedQuarter = $request->input('quarter');

    $startDate = $endDate = null;

    if ($selectedYear && $selectedQuarter) {
        switch ($selectedQuarter) {
            case 'Q1':
                $startDate = Carbon::create($selectedYear, 1, 1)->startOfDay();
                $endDate = Carbon::create($selectedYear, 3, 31)->endOfDay();
                break;
            case 'Q2':
                $startDate = Carbon::create($selectedYear, 4, 1)->startOfDay();
                $endDate = Carbon::create($selectedYear, 6, 30)->endOfDay();
                break;
            case 'Q3':
                $startDate = Carbon::create($selectedYear, 7, 1)->startOfDay();
                $endDate = Carbon::create($selectedYear, 9, 30)->endOfDay();
                break;
            case 'Q4':
                $startDate = Carbon::create($selectedYear, 10, 1)->startOfDay();
                $endDate = Carbon::create($selectedYear, 12, 31)->endOfDay();
                break;
        }
    }

    $grouped = [];

    if ($typeId || ($startDate && $endDate)) {
        $submittedSurveys = SubmittedSurvey::with([
            'commodity:id,name',
            'category:id,name',
            'market:id,name',
            'zone:id,name',
            'brand:id,name',
            'unit:id,name',
            'survey:id,type_id'
        ])
        ->where('publish', 1)
        ->whereNotNull('brand_id')
        ->whereNotNull('unit_id')
        ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
        ->when($typeId, function ($query) use ($typeId) {
            $query->whereHas('survey', fn($q) => $q->where('type_id', $typeId));
        })
        ->get();

        $grouped = $submittedSurveys
            ->filter(fn($item) => $item->category && $item->commodity)
            ->groupBy(fn($item) => $item->category->name)
            ->map(function ($categoryItems) {
                return $categoryItems->groupBy(fn($item) => $item->commodity->id)->map(function ($commodityItems) {
                    $amounts = $commodityItems->pluck('amount')->filter()->sort()->values()->toArray();
                    $count = count($amounts);

                    $median = $count ? ($count % 2 ? $amounts[floor($count / 2)] : ($amounts[$count / 2 - 1] + $amounts[$count / 2]) / 2) : 0;
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
                        'availability'  => $availability
                    ];
                });
            });
    }

    $categories = Category::orderBy('name', 'asc')->get();
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);

    $typeName = match($typeId) {
        1 => 'Food Basket',
        2 => 'Hardware and Building Materials',
        3 => 'Furniture and Appliances',
        4 => 'Medication',
        default => null,
    };

    $filtersApplied = $typeId || ($startDate && $endDate);

    return view('admin.report.surveillance-report', compact(
        'grouped',
        'typeId',
        'selectedYear',
        'selectedQuarter',
        'typeName',
        'filtersApplied',
        'categories',
        'years'
    ));
}
    public function Price_Fluctuations(Request $request)
{
    $request->validate([
        'pf_categories' => 'required|array',
        'pf_commodities' => 'required|array',
        'q_year' => 'required|digits:4',
        'q_selected_quarter' => 'required|in:Q1,Q2,Q3,Q4',
    ]);

    $year = $request->q_year;
    $quarter = $request->q_selected_quarter;

    // Determine start and end dates based on quarter
    switch ($quarter) {
        case 'Q1':
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 3, 31)->endOfDay();
            break;
        case 'Q2':
            $startDate = Carbon::createFromDate($year, 4, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
            break;
        case 'Q3':
            $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 9, 30)->endOfDay();
            break;
        case 'Q4':
            $startDate = Carbon::createFromDate($year, 10, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            break;
    }

    // Build monthly range
    $months = [];
    $currentMonth = $startDate->copy()->startOfMonth();
    while ($currentMonth <= $endDate) {
        $months[] = $currentMonth->format('M Y');
        $currentMonth->addMonth();
    }

    // Fetch and group data
    $submittedSurveys = SubmittedSurvey::with(['commodity', 'brand', 'unit'])
        ->where('publish', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('category_id', $request->pf_categories)
        ->whereIn('commodity_id', $request->pf_commodities)
        ->get();

    $commodityGrouped = $submittedSurveys->groupBy(function ($item) {
        $commodityName = optional($item->commodity)->name;
        $brandName = optional($item->brand)->name;
        $unit = optional($item->unit)->name;
        return "{$commodityName} | {$brandName} | {$unit}";
    })->map(function ($items) {
        return $items->groupBy(function ($item) {
            return $item->created_at->format('M Y');
        })->map(function ($group) {
            return round($group->avg('amount'), 2);
        });
    })->toArray();

    // Normalize monthly data
    $monthGroupedData = [];
    foreach ($months as $month) {
        $monthData = [];
        foreach ($commodityGrouped as $commodity => $data) {
            $monthData[$commodity] = $data[$month] ?? 0;
        }
        $monthGroupedData[$month] = $monthData;
    }

    // Prepare dropdown data
    $categories = Category::orderBy('name', 'asc')->get();
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);

    return view('admin.report.surveillance-report', [
        'pf_groupedData' => $monthGroupedData,
        'pf_categories' => $request->pf_categories,
        'pf_commodities' => $request->pf_commodities,
        'q_year' => $year,
        'q_selected_quarter' => $quarter,
        'categories' => $categories,
        'years' => $years
    ]);
}


   public function comparativeAverage(Request $request)
{
    $request->validate([
        'ca_categories' => 'required|array',
        'ca_commodities' => 'required|array',
        'q_year' => 'required|integer',
        'q_selected_quarter' => 'required|in:Q1,Q2,Q3,Q4',
    ]);

    $year = $request->q_year;
    $quarter = $request->q_selected_quarter;

    // Define quarter ranges
    $quarterRanges = [
        'Q1' => ['start' => "$year-01-01", 'end' => "$year-03-31"],
        'Q2' => ['start' => "$year-04-01", 'end' => "$year-06-30"],
        'Q3' => ['start' => "$year-07-01", 'end' => "$year-09-30"],
        'Q4' => ['start' => "$year-10-01", 'end' => "$year-12-31"],
    ];

    $startDate = Carbon::parse($quarterRanges[$quarter]['start']);
    $endDate = Carbon::parse($quarterRanges[$quarter]['end']);

    $submittedSurveys = SubmittedSurvey::with(['commodity', 'brand', 'unit', 'market'])
        ->where('publish', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('category_id', $request->ca_categories)
        ->whereIn('commodity_id', $request->ca_commodities)
        ->get();

    $groupedData = $submittedSurveys->groupBy([
        fn($item) => trim(
            (optional($item->commodity)->name ?? 'Unknown') . ' | ' .
            (optional($item->brand)->name ?? 'No Brand') . ' | ' .
            (optional($item->unit)->name ?? 'No Unit')
        ),
        fn($item) => optional($item->market)->name ?? 'Unknown'
    ])->map(function ($markets) {
        return $markets->map(fn($items) => round($items->avg('amount'), 2));
    });

    $markets = $submittedSurveys->pluck('market.name')->unique()->filter()->values()->toArray();
    $fullCommodityNames = array_keys($groupedData->toArray());

    $categories = Category::orderBy('name','asc')->get();
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);

    return view('admin.report.surveillance-report', [
        'ca_groupedData' => $groupedData->toArray(),
        'markets' => $markets,
        'fullCommodityNames' => $fullCommodityNames,
        'ca_categories' => $request->ca_categories,
        'ca_commodities' => $request->ca_commodities,
        'q_year' => $year,
        'q_selected_quarter' => $quarter,
        'categories' => $categories,
        'years' => $years,
    ]);
}


  public function PriceFluctuationsPerQuarter(Request $request)
{
    $request->validate([
        'q_year' => 'required|integer|min:2000|max:' . date('Y'),
        'q_categories' => 'required|array',
        'q_commodities' => 'required|array',
        'q_selected_quarters' => 'required|array', // ✅ Validate quarter selection
    ]);

    $year = $request->q_year;
    $selectedQuarters = $request->q_selected_quarters;

    $submittedSurveys = SubmittedSurvey::with(['commodity', 'brand', 'unit'])
        ->where('publish', 1)
        ->whereYear('created_at', $year)
        ->whereIn('category_id', $request->q_categories)
        ->whereIn('commodity_id', $request->q_commodities)
        ->get();

    $grouped = $submittedSurveys->groupBy(function ($item) {
        return trim(
            (optional($item->commodity)->name ?? 'Unknown') . ' | ' .
            (optional($item->brand)->name ?? 'No Brand') . ' | ' .
            (optional($item->unit)->name ?? 'No Unit')
        );
    });

    $quarterlyData = [];

    foreach ($grouped as $label => $items) {
        $data = [];

        foreach (['Q1' => 1, 'Q2' => 2, 'Q3' => 3, 'Q4' => 4] as $quarterLabel => $quarterNum) {
            if (in_array($quarterLabel, $selectedQuarters)) {
                $data[strtolower($quarterLabel)] = round(
                    $items->filter(fn($i) => $i->created_at->quarter === $quarterNum)->avg('amount'),
                    2
                );
            }
        }

        $quarterlyData[$label] = $data;
    }

    $categories = Category::orderBy('name', 'asc')->get();
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);

    return view('admin.report.surveillance-report', [
        'q_quarterlyData' => $quarterlyData,
        'q_year' => $year,
        'q_categories' => $request->q_categories,
        'q_commodities' => $request->q_commodities,
        'q_selected_quarters' => $selectedQuarters, // ✅ Pass selected quarters to view
        'fullCommodityNames' => array_keys($quarterlyData),
        'categories' => $categories,
        'years' => $years
    ]);
}



public function totalAveragePriceReport(Request $request)
{
    $categories = Category::where('status', 1)->get();
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);

    // Validate inputs
    $request->validate([
        'ta_year' => 'required|numeric',
        'ta_quarter' => 'required|in:Q1,Q2,Q3,Q4',
        'ta_categories' => 'required|array',
        'ta_commodities' => 'required|array'
    ]);

    // Define quarter months
    $quarters = [
        'Q1' => ['01', '02', '03'],
        'Q2' => ['04', '05', '06'],
        'Q3' => ['07', '08', '09'],
        'Q4' => ['10', '11', '12'],
    ];

    $year = $request->ta_year;
    $months = $quarters[$request->ta_quarter];
    $start = Carbon::createFromDate($year, $months[0])->startOfMonth();
    $end = Carbon::createFromDate($year, $months[2])->endOfMonth();

    $query = SubmittedSurvey::query()
        ->with(['market', 'commodity'])
        ->where('publish', 1)
        ->whereBetween('created_at', [$start, $end])
        ->whereIn('category_id', $request->ta_categories)
        ->whereIn('commodity_id', $request->ta_commodities);

    $surveys = $query->get();

    $grouped = $surveys->groupBy(['market_id', 'commodity_id']);

    $averages = [];

    foreach ($grouped as $marketId => $commodities) {
        $marketName = optional(optional($commodities->first())->first()->market)->name ?? 'Unknown';
        $marketTotal = 0;

        foreach ($commodities as $records) {
            $monthlyAvgs = [];
            foreach ($months as $month) {
                $monthData = $records->filter(function ($item) use ($month) {
                    return Carbon::parse($item->created_at)->format('m') === $month;
                });

                if ($monthData->count()) {
                    $monthlyAvgs[] = $monthData->avg('amount');
                }
            }

            if (count($monthlyAvgs)) {
                $marketTotal += collect($monthlyAvgs)->avg();
            }
        }

        $averages[] = [
            'location' => $marketName,
            'average' => round($marketTotal, 2),
        ];
    }

    return view('admin.report.surveillance-report', [
        'categories' => $categories,
        'years' => $years,
        'averages' => $averages,
        'ta_year' => $year,
        'ta_quarter' => $request->ta_quarter,
        'ta_categories' => $request->ta_categories ?? [],
        'ta_commodities' => $request->ta_commodities ?? [],
    ]);
}


    public function getCommoditiesByCategories(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);

        $commodities = Commodity::with([
    'brand' => function($query) {
        $query->select('id', 'name');
    },
    'uom' => function($query) {
        $query->select('id', 'name');
    }
])
->whereIn('category_id', $categoryIds)
->orderBy('name', 'asc')
->get(['id', 'name', 'brand_id', 'uom_id']);


        return response()->json($commodities);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['type_id', 'start_date', 'end_date']);

        if (empty($filters['type_id']) && empty($filters['start_date']) && empty($filters['end_date'])) {
            return back()->with('error', 'Please apply at least one filter before exporting.');
        }

        return Excel::download(
            new SurveillanceReportExport($filters),
            'Surveillance_Report_' . now()->format('d-m-Y') . '.xlsx'
        );
    }
}
