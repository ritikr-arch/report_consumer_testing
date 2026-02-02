<?php

namespace App\Http\Controllers\frontend;

use App\Models\Zone;
use App\Models\Type;
use App\Models\Market;
use App\Models\Survey;
use App\Models\Category;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\SurveyCategory;
use App\Models\SubmittedSurvey;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmittedSurveyExport;
use App\Exports\StoreReportExport;
use Illuminate\Support\Facades\DB;


class StoreController extends Controller
{
    public function index(Request $request)
    {

        $title = 'Store';
        $types = Type::where('status', '1')->orderBy('name', 'asc')->get();
        $zonesss = Zone::where('status', '1')->orderBy('name', 'asc')->get();


    $survey_select = Survey::select('id', 'start_date as only_date','type_id')->whereHas('submittedSurveys', function ($query) {
        $query->where('publish', '1')->where('status', '1')->where('is_submit',1);
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('status', '0');
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('publish', '0');
    })
    ->where('is_complete', 1)->where(['is_approve'=>'1', 'status'=>'1'])->get();




        // $query = Survey::where([/*'is_complete'=>1,*/ 'status'=>'1', 'is_approve'=>'1'])->where('status', '1');

        // $query = Survey::whereHas('submittedSurveys', function ($q) {
        //     $q->where('publish', '1')->orderBy('updated_at', 'desc')->limit('1');
        // });
        // // dd($query);
        // if (!empty($request->zone)) {
        //     $query->where('zone_id', $request->zone);
        // }
        // if (!empty($request->start_date)) {
        //     $query->whereDate('start_date', '=', date('Y-m-d', strtotime($request->start_date)));
        // }

        // $survey = $query->where(['is_complete'=>1, 'status'=>'1', 'is_approve'=>'1'])->orderBy('id', 'desc')->first();

        $query = Survey::where('is_complete', 1)
            ->where('status', '1')
            ->where('is_approve', '1')
            ->whereHas('submittedSurveys', function ($q) {
                $q->where('publish', '1');
            })
            ->with(['submittedSurveys' => function ($q) {
                $q->where('publish', '1')->orderBy('updated_at', 'desc')->limit(1);
            }]);

        if (!empty($request->zone)) {
            $query->where('zone_id', $request->zone);
        }

        if (!empty($request->start_date)) {
            $query->whereDate('start_date', '=', date('Y-m-d', strtotime($request->start_date)));
        }

        // Get the latest based on submitted survey's updated_at
        $survey = $query
            ->orderByDesc(
                DB::raw("(SELECT updated_at FROM submitted_surveys WHERE submitted_surveys.survey_id = surveys.id AND publish = 1 ORDER BY updated_at DESC LIMIT 1)")
            )
            ->first();
        
        // $survey = $query->latest()->first();
        if (!$survey) {
            return view('frontend.products', compact('title', 'zonesss', 'survey_select', 'types'))
                ->with('message', 'No survey found for the selected filters.');
        }

        $zone = Zone::find($survey->zone_id);
        $type = Type::find($survey->type_id);
        // $type = Type::find(3);
        if(!empty($type) && (strtolower($type->name) == 'medication')){
            $path = 'medication';
        }else{
           $path = 'products'; 
        }

        $id = $survey->id;
        // dd($id);
        $filterMarkets = Market::where(['zone_id' => $survey->zone_id, 'status' => '1'])
            ->orderBy('name', 'asc')
            ->get();

        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderBy('id', 'desc')->get();
        $categoryy = Category::where(['status'=>'1', 'type_id'=>@$type->id])->orderBy('name', 'asc')->get();

        $allCommodities = Commodity::orderBy('name', 'asc')->get();

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id')->toArray();
        // $marketIds = SubmittedSurvey::whereIn('survey_id', [76,79,82])->pluck('market_id')->toArray();
        $marketIds = array_unique($marketIds);
        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds)
            ->orderBy('name', 'asc')
            ->pluck('id')->toArray();

        $sCategoryIds = SubmittedSurvey::where('survey_id', $id)->pluck('category_id')->toArray();
        $selectedCategories = Category::whereIn('id', $sCategoryIds)->pluck('id')->toArray();
        // Get submitted survey data, ensuring the survey exists before filtering
        // $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
        //     ->where(['survey_id' => $id, 'is_submit' => 1])
        //     ->get()
        //     ->groupBy('category.name');
            // dd($id);

        $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where('is_submit', 1)
            ->where('publish', '1')
            // ->whereIn('survey_id', [76,79,82])
            ->where('survey_id', $id)
            ->get()
            ->groupBy(function($item) {
                // return $item->survey->name ?? 'Unnamed Survey';
                return $item->survey->start_date ?? 'Unnamed Survey';
            })
            ->map(function ($items) {
                    $surveyId = $items->first()->survey_id ?? null;

                    $categories = $items->groupBy(function ($item) {
                        return $item->category->name ?? 'Uncategorized';
                    });

                    return [
                        'survey_id' => $surveyId,
                        'categories' => $categories,
                    ];
                });
        // dd(date('d-m-yy', strtotime($survey->start_date)));
        return view("frontend.$path", compact('title', 'zonesss', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'survey_select', 'types', 'type', 'categoryy', 'selectedCategories'));
    }

    public function filter(Request $request){
        $title = 'Store';
        $types = Type::where('status', '1')->orderBy('name', 'asc')->get();
        $zonesss = Zone::where('status', '1')->orderBy('name', 'asc')->get();

      $survey_select = Survey::select('id', 'start_date as only_date','type_id')->whereHas('submittedSurveys', function ($query) {
        $query->where('publish', '1')->where('status', '1')->where('is_submit',1);
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('status', '0');
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('publish', '0');
    })
    ->where('is_complete', 1)->where('type_id', $request->type)->where(['is_approve'=>'1', 'status'=>'1'])->get();

        // $query = Survey::where(['is_complete'=>1, 'status'=>'1', 'is_approve'=>'1'])->where('status', '1');
        // if (!empty($request->zone)) {
        //     $query->where('zone_id', $request->zone);
        // }
        // if (!empty($request->start_date)) {
        //     $query->whereDate('start_date', '=', date('Y-m-d', strtotime($request->start_date)));
        // }

        // $survey = $query->latest()->first();
        $query = Survey::where(['is_complete'=>1,'status'=>'1', 'is_approve'=>'1']);
        if(!empty($request->type) && array_filter($request->type)){
            if($request->type['0']){
                $surveyType = Type::find($request->type['0']);
                if(!empty($surveyType) && (strtolower($surveyType->name) == 'medication')){
                    $path = 'medication';
                }else{
                    $path = 'products';
                }
            }else{
                $path = 'products';
            }
            $query->whereIn('type_id', $request->type);   
        }

        if (!empty($request->zone) && array_filter($request->zone)) {
            $query->whereIn('zone_id', $request->zone);
            $filterMarkets = Market::whereIn('zone_id', $request->zone)->where('status', '1')
                ->orderBy('name', 'asc')
                ->get();
        }else{
            $filterMarkets = [];
        }

        // if (!empty($request->zone) && array_filter($request->zone)) {
        //     $query->whereIn('zone_id', $request->zone);
        // }

        if (!empty($request->start_date) && array_filter($request->start_date)) {
    // Convert each date from d-m-Y to Y-m-d
    $convertedDates = array_map(function ($date) {
        return \Carbon\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    }, $request->start_date);

    $query->whereIn(DB::raw('DATE(start_date)'), $convertedDates);
}

        $survey = $query->orderBy('start_date', 'desc')->get();
        // dd($survey->pluck('id'));
        $type = Type::find($request->type['0']);
        $categoryIds = SurveyCategory::whereIn('survey_id', $survey->pluck('id'))->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderBy('id', 'desc')->get();

        $categoryy = Category::where(['status'=>'1', 'type_id'=>@$type->id])->orderBy('name', 'asc')->get();

        if (count($survey)<=0) {
            return view('frontend.products', compact('title', 'zonesss', 'survey_select', 'types' ,'filterMarkets', 'filterCategories', 'categoryy'))
                ->with('message', 'No survey found for the selected filters.');
        }

        $zone = Zone::find(@$survey['0']->zone_id);
        // $type = Type::find(@$survey['0']->type_id);  // OLD CODE
        
        // $type = Type::find(3);

        $id = @$survey->pluck('id')->toArray();
        
        // $filterMarkets = Market::whereIn('zone_id', $survey->pluck('zone_id'))->where('status', '1')
        //     ->orderBy('name', 'asc')
        //     ->get();

        // OLD CODE START
        // if(!empty($request->market) && array_filter($request->market)){
        //     $filterMarkets = Market::whereIn('id', $request->market)->where('status', '1')
        //         ->orderBy('name', 'asc')
        //         ->get();
        // }else{
        //     $filterMarkets = [];
        // }
        // OLD CODE END


        $allCommodities = Commodity::orderBy('name', 'asc')->get();

        // $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');
        $marketIds = SubmittedSurvey::whereIn('survey_id', $survey->pluck('id'))->pluck('market_id')->toArray();
        $marketIds = array_unique($marketIds);
        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds)
            ->orderBy('name', 'asc')
            ->pluck('id')->toArray();

        $sCategoryIds = SubmittedSurvey::whereIn('survey_id', $survey->pluck('id'))->pluck('category_id')->toArray();
        $selectedCategories = Category::whereIn('id', $sCategoryIds)->pluck('id')->toArray();
        // dd($selectedCategories);
        // Get submitted survey data, ensuring the survey exists before filtering
        // $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
        //     ->where(['survey_id' => $id, 'is_submit' => 1])
        //     ->get()
        //     ->groupBy('category.name');
            // dd($survey->pluck('id'));

        $surveyIds = $survey->pluck('id')->toArray();
        $query = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where('is_submit', 1)
            ->where('publish', '1')
            ->whereIn('survey_id', $survey->pluck('id'))
            ->orderByRaw('FIELD(survey_id, ' . implode(',', $surveyIds) . ')');
            
        if (!empty($request->category) && array_filter($request->category)) {
            $query->whereIn('category_id', $request->category);
        }
        if (!empty($request->market) && array_filter($request->market)) {
            $query->whereIn('market_id', $request->market);
        }

        $data = $query->get()
                ->groupBy(function($item) {
                    return $item->survey->id ?? 'Unnamed Survey';
                    // return $item->survey->start_date ?? 'Unnamed Survey';
                    // return $item->survey->name ?? 'Unnamed Survey';
                })
                ->map(function ($items, $startDate) {
                    $surveyId = $items->first()->survey_id ?? null;

                    $categories = $items->groupBy(function ($item) {
                        return $item->category->name ?? 'Uncategorized';
                    });

                    return [
                        // 'survey_id' => $surveyId,
                        // 'categories' => $categories,
                        'survey_id'  => $surveyId,
                        'start_date' => $startDate,
                        'categories' => $categories,
                    ];
                });
        $survey = @$survey['0']; 
        // dd($data);
        return view("frontend.$path", compact('title', 'zonesss', 'data', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'survey_select', 'types', 'type', 'categoryy', 'selectedCategories'));
    }

    public function report_download(Request $request)
    {
        $filters = $request->only(['survey_number', 'name', 'zone', 'publish', 'status', 'start_date', 'end_date']);
        return Excel::download(new SubmittedSurveyExport($filters), 'submitted-surveys.xlsx');
        
    }

    public function typeCategory(Request $request){
        $categories = Category::where('type_id', $request->type)->where('status', '1')->orderBy('name', 'asc')->get();
        if(count($categories)>0){
            return response()->json(['success' => true, 'data'=>$categories]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function typeMarkets(Request $request){
        $markets = Market::whereIn('zone_id', $request->zones)->where('status', '1')->orderBy('name', 'asc')->get();
        if(count($markets)>0){
            return response()->json(['success' => true, 'data'=>$markets]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function storeMobile(Request $request){
        $url = $request->query();
        $title = 'Store';
        $types = Type::where('status', '1')->orderBy('name', 'asc')->get();
        $zonesss = Zone::where('status', '1')->orderBy('name', 'asc')->get();

   $survey_select = Survey::select('id', 'start_date as only_date','type_id')->whereHas('submittedSurveys', function ($query) {
        $query->where('publish', '1')->where('status', '1')->where('is_submit',1);
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('status', '0');
    })
    ->whereDoesntHave('submittedSurveys', function ($query) {
        $query->where('publish', '0');
    })
    ->where('is_complete', 1)->where(['is_approve'=>'1', 'status'=>'1'])->get();

        $query = Survey::where('is_complete', 1)
            ->with('categories.surveyCategoriesss')
            ->where('status', '1')
            ->where('is_approve', '1')
            ->whereHas('submittedSurveys', function ($q) {
                $q->where('publish', '1');
            })
            ->with(['submittedSurveys' => function ($q) {
                $q->where('publish', '1')->orderBy('updated_at', 'desc')->limit(1);
            }]);

        if (!empty($request->zone)) {
            $query->where('zone_id', $request->zone);
        }

        if (!empty($request->start_date)) {
            $query->whereDate('start_date', '=', date('Y-m-d', strtotime($request->start_date)));
        }

        $survey = $query
            ->orderByDesc(
                DB::raw("(SELECT updated_at FROM submitted_surveys WHERE submitted_surveys.survey_id = surveys.id AND publish = 1 ORDER BY updated_at DESC LIMIT 1)")
            )
            ->first();
        
        if (!$survey) {
            return view('frontend.products', compact('title', 'zonesss', 'survey_select', 'types'))
                ->with('message', 'No survey found for the selected filters.');
        }

        $zone = Zone::find($survey->zone_id);
        $type = Type::find($survey->type_id);
        if(!empty($type) && (strtolower($type->name) == 'medication')){
            $path = 'medication';
        }else{
           $path = 'products'; 
        }

        $id = $survey->id;
        $filterMarkets = Market::where(['zone_id' => $survey->zone_id, 'status' => '1'])
            ->orderBy('name', 'asc')
            ->get();

        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderBy('id', 'desc')->get();
        $categoryy = Category::where(['status'=>'1', 'type_id'=>@$type->id])->orderBy('name', 'asc')->get();

        $allCommodities = Commodity::orderBy('name', 'asc')->get();

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id')->toArray();
        $marketIds = array_unique($marketIds);
        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds)
            ->orderBy('name', 'asc')
            ->pluck('id')->toArray();

        $sCategoryIds = SubmittedSurvey::where('survey_id', $id)->pluck('category_id')->toArray();
        $selectedCategories = Category::whereIn('id', $sCategoryIds)->pluck('id')->toArray();

        $path = 'products';
        return view("frontend.mobileView.$path", compact('title', 'zonesss', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'survey_select', 'types', 'type', 'categoryy', 'selectedCategories'));
    }

    public function exportReport(Request $request)
{
    $types = Type::find($request->type[0]);
    $type = strtolower($types->name);

    $query1 = Survey::where(['is_complete' => 1, 'status' => '1', 'is_approve' => '1']);

    if (!empty($request->type)) {
        $query1->whereIn('type_id', $request->type);
    }

    if (!empty($request->zone)) {
        $query1->whereIn('zone_id', $request->zone);
    }

    $dates = [];
    foreach ($request->start_date as $value) {
        $dates[] = date('Y-m-d', strtotime($value));
    }

    if (!empty($request->start_date)) {
        $query1->whereIn(DB::raw('DATE(start_date)'), $dates);
    }

    $surveys = $query1->orderBy('start_date', 'desc')->get();
    $surveyIds = $surveys->pluck('id');

    $query = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey.zone', 'brand', 'unit'])
        ->where('is_submit', 1)
        ->where('publish', 1)
        ->whereIn('survey_id', $surveyIds);

    if (!empty($request->category)) {
        $query->whereIn('category_id', $request->category);
    }

    if (!empty($request->market)) {
        $query->whereIn('market_id', $request->market);
    }

    $submittedSurveys = $query->get();

    // Group by survey_id to separate reports for same date
    $grouped = $submittedSurveys->groupBy('survey_id');

    $data = [];
    $maxMarketCount = 0;

    foreach ($grouped as $surveyId => $items) {
        $categories = $items->groupBy(fn($item) => $item->commodity->category->name ?? 'Unknown');
        $marketIds = $items->pluck('market_id')->unique();
        $maxMarketCount = max($maxMarketCount, count($marketIds));

        $data[$surveyId] = [
            'survey_id' => $surveyId,
            'categories' => $categories,
        ];
    }

    return Excel::download(new StoreReportExport($data, $maxMarketCount, $type), "Price-collected-Report.xlsx");
}

   public function getHighlightedDates(Request $request)
{
    $typeId = $request->type_id;

    $surveys = Survey::select('id', 'start_date as only_date', 'type_id')
        ->whereHas('submittedSurveys', function ($query) {
            $query->where('publish', '1')
                  ->where('status', '1')
                  ->where('is_submit', 1);
        })
        ->whereDoesntHave('submittedSurveys', function ($query) {
            $query->where('status', '0');
        })
        ->whereDoesntHave('submittedSurveys', function ($query) {
            $query->where('publish', '0');
        })
        ->where('is_complete', 1)
        ->where('type_id', $typeId)
        ->where(['is_approve' => '1', 'status' => '1'])
        ->get();

    // Transform the data into a more frontend-friendly format
    $highlightedDates = [];
    foreach ($surveys as $survey) {
        $date = date('Y-m-d', strtotime($survey->only_date));
        $highlightedDates[$date] = $survey->type_id;
    }

    return response()->json($highlightedDates);
}


}
