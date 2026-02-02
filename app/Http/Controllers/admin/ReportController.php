<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\SubmittedSurveyExport;

use App\Exports\SubmittedSurveyReportExport;
use App\Exports\SubmittedSurveyMedicationReportExport;
use App\Exports\PriceAnalysisReportExport;
use DB;
use auth;

use App\Models\User;
use App\Models\Zone;
use App\Models\Type;
use App\Models\Survey;
use App\Models\Market;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\SurveyMarket;
use App\Models\SurveyCategory;
use App\Models\SubmittedSurvey;


class ReportController extends Controller
{

    public function __construct (){
        $this->middleware('permission:submit_survey_list', ['only' => ['index']]);
        $this->middleware('permission:submit_survey_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:submit_survey_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:submit_survey_delete', ['only' => ['delete']]);
    }

    public function index(){
        $title = 'Survey Report List';
        $surveyIds = SubmittedSurvey::groupBy('survey_id')
                                    ->selectRaw('MAX(survey_id) as id')->pluck('id');
        $zone = Zone::where('status', '1')->orderby('id', 'desc')->get();

        $category = Category::where('status', '1')->orderby('name', 'asc')->get();
        $surveyor = User::whereHas('roles', function($query){
            $query->where('name', 'Compliance Officer');
        })->orderby('name', 'asc')->get();
        $data = Survey::with('zone', 'markets', 'categories', 'surveyors')->whereIn('id', $surveyIds)->orderby('id', 'desc')->paginate(10);
    
        return view('admin.report.index', compact('title', 'data', 'zone', 'category', 'surveyor'));
    }

    public function filter(Request $request){
        $title = 'Survey Report List';

        $surveyIds = SubmittedSurvey::groupBy('survey_id')
                                    ->selectRaw('MAX(survey_id) as id')->pluck('id');

        $zone = Zone::where('status', '1')->orderby('name', 'asc')->get();

        $category = Category::where('status', '1')->orderby('name', 'asc')->get();

        $surveyor = User::orderby('name', 'asc')->get();

        $query = Survey::with('zone', 'markets', 'categories', 'surveyors')->whereIn('id', $surveyIds);

        $marktes = [];

        if($request->name){
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if($request->survey_id){
            $survey_id = preg_replace('/[^a-zA-Z0-9]/', '', $request->survey_id);
            $query->where('survey_id', $survey_id);
        }

        if($request->zone){
            $marktes = Market::select('id', 'zone_id', 'name')
                                ->where(['zone_id'=>$request->zone, 'status'=>'1'])
                                ->orderby('id', 'desc')->get();
            $query->where('zone_id', $request->zone);
        }

        if($request->market){
            $query->whereHas('markets', function ($q) use ($request) {
                $q->where('market_id', $request->market);
            });
        }

        if($request->category){
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if($request->surveyor){
            $query->whereHas('surveyors', function ($q) use ($request) {
                $q->where('surveyor_id', $request->surveyor);
            });
        }

        if(($request->status === '0') || ($request->status === '1') ){
            $query->where('status', $request->status);
        }

        if($request->start_date){
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }

        if($request->end_date){
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.report.index', compact('title', 'data', 'zone', 'surveyor', 'category', 'marktes'));
    }

    public function surveyReportDetails($id){
        $title = 'Survey Report Details';
        $survey = Survey::with('surveyType')->find($id);
        $type = ($survey->surveyType)?$survey->surveyType->name:'';
        $path = (strtolower($type) == 'medication')?'medication':'details';
        $zone = Zone::where('id', @$survey->zone_id)->first();

        $filterMarkets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
                                ->orderby('name', 'asc')->get();
                                // dd($survey->zone_id);
        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

        $allCommodities = Commodity::orderby('name', 'asc')->get();

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');
        
        $markets = Market::where('status', '1')
                ->whereIn('id', $marketIds) 
                ->orderBy('name', 'asc')
                ->get();

        $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where(['survey_id' => $id, /*'is_submit' => 1*/])
            // ->whereIn('survey_id', [79,78])
            ->get()
            ->groupBy('category.name'); 

        // dd($data);
        $types = Type::orderby('name', 'asc')->get();

        return view("admin.report.$path", compact('title', 'data', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'types'));
    }

    public function surveyReportDetailsOLD($id){
        $title = 'Survey Report Details';
        $survey = Survey::find($id);
        // dd($survey->zone_id);
        $zones = Zone::orderby('name', 'asc')->get();
        
        $markets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
                            ->orderby('name', 'asc')->get();

        $filterMarkets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
                            ->orderby('name', 'asc')->get();

        $surveys = Category::whereHas('submittedSurveys', function($query) use($id){
            $query->where(['is_submit'=>1, 'survey_id'=>$id]);
        })->with('submittedSurveys')->orderby('name', 'asc')->get();

        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');
        
        $category = Category::whereHas('commodities', function($queryy){
            $queryy->where('status', '1');
        })->with('commodities.brand', 'commodities.uom')->whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

        $categories = Category::whereHas('commodities', function($queryy){
            $queryy->where('status', '1');
        })->with('commodities.brand', 'commodities.uom')->whereIn('id', $categoryIds)->orderby('id', 'desc')->get();
        
        $prices = SubmittedSurvey::where('survey_id', $id)
            ->get()
            ->groupBy(function ($item) {
                return $item->commodity_id . '-' . $item->market_id;
            });
            // dd($prices);
        $zoneName = 'Test';
        return view('admin.report.details', compact('title', 'categories', 'markets', 'filterMarkets', 'prices', 'zones', 'zoneName', 'category', 'id'));
    }

    public function reportDetailsFilter(Request $request){
        // dd($request->all());
        $title = 'Survey Report Details';
        $id = $request->id;
        $survey = Survey::with('surveyType')->find($id);
        $type = ($survey->surveyType)?$survey->surveyType->name:'';
        $path = (strtolower($type) == 'medication')?'medication':'details';
        $zone = Zone::where('id', @$survey->zone_id)->first();

        // $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');
        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

        $filterMarkets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
                                ->orderby('name', 'asc')->get();

        $allCommodities = Commodity::orderby('name', 'asc')->get();

        $types = Type::orderby('name', 'asc')->get();

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');

        $markets = Market::where('status', '1')
            ->whereIn('id', $marketIds)
            ->orderBy('name', 'asc')
            ->get();

        $query = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where(['survey_id' => $id, /*'is_submit' => 1*/]);

        // Apply filters based on request parameters
        $query->when($request->filled('market'), function ($q) use ($request) {
            return $q->where('market_id', $request->market);
        });

        $query->when($request->filled('category'), function ($q) use ($request) {
            return $q->where('category_id', $request->category);
        });

            // dd("sfsdjlfjsdl");
        $query->when($request->filled('name'), function ($q) use ($request) {
            return $q->where('commodity_id', $request->name);
        });

        $query->when($request->filled('unit_id'), function ($q) use ($request) {
            return $q->where('unit_id', $request->unit_id);
        });

        $query->when($request->filled('brand_id'), function ($q) use ($request) {
            return $q->where('brand_id', $request->brand_id);
        });

        $query->when($request->filled('amount'), function ($q) use ($request) {
            return $q->where('amount', $request->amount);
        });

        $query->when($request->filled('availability'), function ($q) use ($request) {
            return $q->where('availability', $request->availability);
        });

        $query->when($request->filled('submitted_by'), function ($q) use ($request) {
            return $q->where('submitted_by', $request->submitted_by);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        });

        $query->when($request->filled('publish'), function ($q) use ($request) {
            return $q->where('publish', $request->publish);
        });

        $query->when($request->filled('commodity_expiry_date'), function ($q) use ($request) {
            return $q->whereDate('commodity_expiry_date', $request->commodity_expiry_date);
        });

        $query->when($request->filled('start_date'), function ($q) use ($request) {
            return $q->whereDate('created_at', '>=', $request->start_date);
        });

        $query->when($request->filled('end_date'), function ($q) use ($request) {
            return $q->whereDate('created_at', '<=', $request->end_date);
        });


        // Fetch filtered results
        $data = $query->get()->groupBy('category.name');
        // dd($data);
        return view("admin.report.$path", compact('title', 'data', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories','zone', 'survey', 'types'));
    }

    
    public function reportDetailsFilterBKP(Request $request)
    {
        $id = $request->id;
        $title = 'Survey Report Details';
        $survey = Survey::find($id);
        $zoneName = 'Test';
        $zones = Zone::orderby('name', 'asc')->get();

        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');
        $category = Category::whereHas('commodities', function($queryy){
            $queryy->where('status', '1');
        })->with('commodities.brand', 'commodities.uom')->whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

        if($request->market){
            $markets = Market::where(['id' => $request->market, 'status' => '1'])
                        ->orderby('name', 'asc')->get();
        }

        $filterMarkets = Market::where(['zone_id' => @$survey->zone_id, 'status' => '1'])
                        ->orderby('name', 'asc')->get();

        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $categories = Category::whereHas('commodities', function ($query) {
                $query->where('status', '1');
            })
            ->with('commodities.brand', 'commodities.uom')
            ->whereIn('id', $categoryIds)
            ->orderby('id', 'desc')
            ->get();

        // Start filtering SubmittedSurvey
        $query = SubmittedSurvey::where('survey_id', $id);

        // Filter by commodity name
        if ($request->filled('commodity_name')) {
            $query->whereHas('commodity', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->commodity_name . '%');
            });
        }

        // Filter by market ID
        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }

        // Filter by category ID
        if ($request->filled('category_id')) {
            $query->whereHas('commodity.category', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        // Filter by created_at date range
        if ($request->filled('created_from') && $request->filled('created_to')) {
            $query->whereBetween('created_at', [$request->created_from, $request->created_to]);
        }

        // Filter by updated_at date range
        if ($request->filled('updated_from') && $request->filled('updated_to')) {
            $query->whereBetween('updated_at', [$request->updated_from, $request->updated_to]);
        }

        // Fetch filtered prices
        $prices = $query->get()->groupBy(function ($item) {
            return $item->commodity_id . '-' . $item->market_id;
        });

        return view('admin.report.details', compact(
            'title', 'categories', 'markets', 'filterMarkets', 'prices', 
            'zones', 'zoneName', 'category', 'id'
        ));
    }


    // public function reportDetailsFilter(Request $request)
    // {
    //     $title = 'Survey Report Details';
    //     $query = SubmittedSurvey::query(); // Start the query

    //     // Filter by commodity name (Assuming Commodity has a relationship)
    //     if ($request->filled('commodity_name')) {
    //         $query->whereHas('commodity', function ($q) use ($request) {
    //             $q->where('name', 'LIKE', '%' . $request->commodity_name . '%');
    //         });
    //     }

    //     // Filter by market ID
    //     if ($request->filled('market_id')) {
    //         $query->where('market_id', $request->market_id);
    //     }

    //     // Filter by category ID (Assuming Commodity has a relationship with Category)
    //     if ($request->filled('category_id')) {
    //         $query->whereHas('commodity.category', function ($q) use ($request) {
    //             $q->where('id', $request->category_id);
    //         });
    //     }

    //     // Filter by created_at date range
    //     if ($request->filled('created_from') && $request->filled('created_to')) {
    //         $query->whereBetween('created_at', [$request->created_from, $request->created_to]);
    //     }

    //     // Filter by updated_at date range
    //     if ($request->filled('updated_from') && $request->filled('updated_to')) {
    //         $query->whereBetween('updated_at', [$request->updated_from, $request->updated_to]);
    //     }

    //     // Get results
    //     $filteredSurveys = $query->get();

    //     // return response()->json($filteredSurveys);
    //     $zoneName = 'Test';
    //     return view('admin.report.details', compact('title', 'categories', 'markets', 'filterMarkets', 'prices', 'zones', 'zoneName', 'category', 'id'));
    // }

    // public function reportDetailsFilter(Request $request){
    //     $id = $request->id;
    //     $title = 'Survey Report Details';
    //     $survey = Survey::find($id);
    //     $zones = Zone::orderby('name', 'asc')->get();
        
    //     $filterMarkets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
    //                         ->orderby('name', 'asc')->get();

    //     $surveys = Category::whereHas('submittedSurveys', function($query) use($id){
    //         $query->where(['is_submit'=>1, 'survey_id'=>$id]);
    //     })->with('submittedSurveys')->orderby('name', 'asc')->get();

    //     $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

    //     $category = Category::whereHas('commodities', function($queryy){
    //         $queryy->where('status', '1');
    //     })->with('commodities.brand', 'commodities.uom')->whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

    //     // $categories = Category::whereHas('commodities', function($queryy){
    //     //     $queryy->where('status', '1');
    //     // })->with('commodities.brand', 'commodities.uom')->whereIn('id', $categoryIds)->orderby('id', 'desc')->get();
    //     $nameCommodityIds = [];
    //     if($request->name){
    //         $nameCommodityIds = Commodity::where('name', 'like', '%'.$request->name.'%')
    //                                 ->where('status', '1')->pluck('category_id');
    //     }

    //     // $categoryQuery = Category::whereHas('commodities', function($queryy) use($request) {
    //     //     // $queryy->where('status', '1');
    //     //     // if ($request->name) {
    //     //         $queryy->where('name', 'like', '%' . $request->name . '%');
    //     //     // }
    //     // })->with(['commodities', 'commodities.brand', 'commodities.uom'])->get();

    //     $surveyCommodityId = [];
    //     $surveyCommodityId1 = [];

    //     if($request->name){
    //         $commodityIds = Commodity::where('name', 'like', '%'.$request->name.'%')->pluck('id');
    //         $surveyCommodityId = SubmittedSurvey::whereIn('commodity_id', $commodityIds)
    //                                                 ->where('survey_id', $id)->pluck('commodity_id');
    //     }
    //     if($request->market){
    //         $markets = Market::where(['id'=>@$request->market, 'status'=>'1'])->get();
    //         $surveyCommodityId1 = SubmittedSurvey::where('market_id', $request->market)
    //                                                 ->where('survey_id', $id)->pluck('commodity_id');
    //     }
    //     $categoryQuery = Category::whereHas('commodities', function($queryy) use ($surveyCommodityId, $request, $surveyCommodityId1) {
    //         $queryy->where('status', '1');
    //         if($request->name){
    //             $queryy->whereIn('id', $surveyCommodityId);
    //         }
    //         if($request->market){
    //             $queryy->whereIn('id', $surveyCommodityId1);
    //         }
    //     })->with(['commodities' => function ($queryy) use ($surveyCommodityId, $request) {
    //         if($request->name){
    //             // $commodityIds = Commodity::where('name', 'like', '%'.$request->name.'%')->pluck('id');
    //             $queryy->whereIn('id', $surveyCommodityId);
    //         }
    //     }, 'commodities.brand', 'commodities.uom']);

    //     // $prices = SubmittedSurvey::where('survey_id', $id)
    //     //     ->get()
    //     //     ->groupBy(function ($item) {
    //     //         return $item->commodity_id . '-' . $item->market_id;
    //     //     });

    //     $pricesQuery = SubmittedSurvey::where('survey_id', $id);

    //     if($request->category){
    //         $categoryQuery->where('id', $request->category);
    //         $pricesQuery->where('category_id', $request->category_id);
    //     }
    //     // if(!$request->category){
    //     //     $categoryQuery->whereIn('id', $categoryIds);
    //     // }

    //     if($request->market){
    //         $pricesQuery->where('market_id', $request->market);
    //     }

    //     // if($request->name){
    //     //     $commodity = Commodity::where('name', 'like', '%'.$request->name.'%')
    //     //                             ->where('status', '1')->pluck('category_id');
    //     //                             // dd($commodity);
    //     //     // $pricesQuery->whereIn('commodity_id', $commodity);
    //     //     $categoryQuery->whereIn('id', $commodity);
    //     // }
        
    //     $prices = $pricesQuery->get()->groupBy(fn($item) => $item->commodity_id . '-' . $item->market_id);
    //     $categories = $categoryQuery->orderby('id', 'desc')->get(); 
    //     // dd($categories);

    //     $zoneName = 'Test';
    //     return view('admin.report.details', compact('title', 'categories', 'markets', 'filterMarkets', 'prices', 'zones', 'zoneName', 'category', 'id'));
    // }

    public function exportSurveyReport(Request $request){
        // dd($request->id);
        if (!isset($request->id) || empty($request->id)) {
            return back()->with('error', 'Survey ID is required.');
        }

        // $filters = $request->only(['id', 'name', 'category', 'market', 'start_date', 'end_date']);
        $filters = array_merge(['id' => $request->id], $request->only(['name', 'category', 'market', 'start_date', 'end_date']));
        // dd($filters);
        $survey = Survey::select('name')->where('id', $request->id)->first();
        
        return Excel::download(new SubmittedSurveyReportExport($filters), $survey->name . '.xlsx');
    }

    public function exportMedicationSurveyReport(Request $request){
        if (!isset($request->id) || empty($request->id)) {
            return back()->with('error', 'Survey ID is required.');
        }

        $filters = array_merge(['id' => $request->id], $request->only(['name', 'category', 'market', 'start_date', 'end_date']));
        $survey = Survey::select('name')->where('id', $request->id)->first();
        return Excel::download(new SubmittedSurveyMedicationReportExport($filters), $survey->name . '.xlsx');
    }

    public function exportPriceAnalysisReport($id){
        $title = 'Survey Report Details';
        $survey = Survey::find($id);

        $zone = Zone::where('id', @$survey->zone_id)->first();

        $filterMarkets = Market::where(['zone_id'=>@$survey->zone_id, 'status'=>'1'])
                                ->orderby('name', 'asc')->get();
                                
        $categoryIds = SurveyCategory::where('survey_id', $id)->pluck('category_id');

        $filterCategories = Category::whereIn('id', $categoryIds)->orderby('id', 'desc')->get();

        $allCommodities = Commodity::orderby('name', 'asc')->get();

        $marketIds = SubmittedSurvey::where('survey_id', $id)->pluck('market_id');
        
        $markets = Market::where('status', '1')
                ->whereIn('id', $marketIds) 
                ->orderBy('name', 'asc')
                ->get();

        $totalMarkets = SurveyMarket::where('survey_id', $id)->count();

        $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where(['survey_id' => $id, /*'is_submit' => 1*/])
            ->get()
            ->groupBy('category.name'); 

        return view('admin.report.price_analysis', compact('title', 'data', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'totalMarkets'));
    }

    public function export_price_analysis_report(Request $request){
        if(!isset($request->id) || empty($request->id)){
            return back()->with('error', 'Survey ID is required.');
        }

       $filters = array_merge(['id' => $request->id], $request->only(['name', 'category', 'market', 'start_date', 'end_date']));
       $survey = Survey::select('name')->where('id', $request->id)->first();
        return Excel::download(new PriceAnalysisReportExport($filters), 'Price-Observation-for-'.$survey->name.'.xlsx');
    }


}

