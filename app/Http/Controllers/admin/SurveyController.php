<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use App\Http\Traits\ImageTrait;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\SurveyExport;

use Carbon\Carbon;

use App\Models\User;

use App\Models\Zone;

use App\Models\Market;

use App\Models\Survey;

use App\Models\Category;

use App\Models\Commodity;

use App\Models\SurveyMarket;

use App\Models\SurveyProduct;

use App\Models\SurveySurveyor;

use App\Models\SurveyCategory;

use App\Models\SubmittedSurvey;

use App\Models\Notification;
use App\Models\Type;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware("permission:survey_list", ["only" => ["index"]]);

        $this->middleware("permission:survey_create", [
            "only" => ["create", "store"],
        ]);

        $this->middleware("permission:survey_edit", [
            "only" => ["edit", "update", "changeStatus"],
        ]);

        $this->middleware("permission:survey_delete", ["only" => ["delete"]]);
    }

    public function index()
    {
        $title = "Survey List";
        $zone = Zone::where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $category = Category::where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $surveyor = User::whereHas("roles", function ($query) {
            $query->where("name", "Compliance Officer");
        })
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();

        $investigationOfficer = User::role("Investigation Officer")
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $chiefofficer = User::role("Chief Investigation Officer")
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $type = Type::where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $data = Survey::with("zone", "markets", "categories", "surveyors")
            ->orderby("id", "desc")
            ->paginate(10);
            // dd($data);
            return view("admin.survey.index",
            compact("title","data","zone","surveyor","category","investigationOfficer","chiefofficer", "type")
        );
    }

    public function filter(Request $request)
    {
        $title = "Survey List";

        $investigationOfficer = User::role("Investigation Officer")
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $chiefofficer = User::role("Chief Investigation Officer")
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();
        $type = Type::where("status", "1")
            ->orderby("name", "asc")
            ->get();

        $zone = Zone::where("status", "1")
            ->orderby("name", "asc")
            ->get();

        $category = Category::where("status", "1")
            ->orderby("name", "asc")
            ->get();

        // $surveyor = User::orderby('name', 'asc')->get();
        $surveyor = User::whereHas("roles", function ($query) {
            $query->where("name", "Compliance Officer");
        })
            ->where("status", "1")
            ->orderby("name", "asc")
            ->get();

        $query = Survey::with("zone", "markets", "categories", "surveyors", "investigationOfficer", "chiefofficer", "type");

        $marktes = [];
        $filterMarket = [];    
        if ($request->name) {
            $query->where("name", "like", "%" . $request->name . "%");
        }

        if ($request->survey_id) {
            $survey_id = preg_replace(
                "/[^a-zA-Z0-9]/",
                "",
                $request->survey_id
            );

            $query->where("survey_id", $survey_id);
        }

        if ($request->zone) {
            $filterMarket = Market::select("id", "zone_id", "name")

                ->where(["zone_id" => $request->zone, "status" => "1"])

                ->orderby("id", "desc")
                ->get();

            $query->where("zone_id", $request->zone);
        }

        if ($request->market) {
            $query->whereHas("markets", function ($q) use ($request) {
                $q->where("market_id", $request->market);
            });
        }

        if ($request->category) {
            $query->whereHas("categories", function ($q) use ($request) {
                $q->where("category_id", $request->category);
            });
        }

        if ($request->surveyor) {
            $query->whereHas("surveyors", function ($q) use ($request) {
                $q->where("surveyor_id", $request->surveyor);
            });
        }

        if ($request->status === "0" || $request->status === "1") {
            $query->where("status", $request->status);
        }

        if ($request->start_date) {
            $query->whereDate(
                "created_at",
                ">=",
                date("Y-m-d", strtotime($request->start_date))
            );
        }

        if ($request->end_date) {
            $query->whereDate(
                "created_at",
                "<=",
                date("Y-m-d", strtotime($request->end_date))
            );
        }

        $data = $query
            ->orderby("id", "desc")
            ->paginate(10)
            ->withQueryString();

        return view(
            "admin.survey.index",

            compact("title", "data", "zone", "surveyor", "category", "investigationOfficer", "chiefofficer", "type", 'filterMarket')
        );
    }

    public function getZoneMarket(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id))
        {
            $data = Market::select("id", "zone_id", "name")
                    ->whereIn("zone_id", (array) $id)
                    ->where("status", "1")
                    ->orderby("name", "asc")
                    ->get();
            

            if ($data) {
                return response()->json(["success" => true, "data" => $data]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "No record found.",
                ]);
            }
        }
        else
        {
            return response()->json([
                    "success" => false,
                    "message" => "No record found.",
                ]);
        }
    }


    public function getTypeCategory(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id))
        {
            $data = Category::select("id", "type_id", "name")
                    ->whereIn("type_id", (array) $id)
                    ->where("status", "1")
                    ->orderby("name", "asc")
                    ->get();
            

            if ($data) {
                return response()->json(["success" => true, "data" => $data]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "No record found.",
                ]);
            }
        }
        else
        {
            return response()->json([
                    "success" => false,
                    "message" => "No record found.",
                ]);
        }
    }

    public function categoryProduct($id)
    {
        $data = Commodity::select("id", "category_id", "name")

            ->where(["category_id" => $id, "status" => "1"])
            ->get();

        if ($data) {
            return response()->json(["success" => true, "data" => $data]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No record found.",
            ]);
        }
    }

    public function save(Request $request)
    {
        // return $request->all();
        $rules = [
            "name" => "required|max:250",
            "zone" => "required",
            // "investigation" => "required",
            "type" => "required",
            // "chiefinvestigation" => "required",
            "market" => "required|array",
            "category" => "required|array",
            "surveyor" => "required|array",
            "from_date" => "required|date",
            "to_date" => "required|date|after_or_equal:from_date",
            "status" => "required|in:0,1",
            // "investigation" => "required"
        ];
 
        $messages = [
            'market.required' => 'The store field is required.',
            'surveyor.required' => 'The compliances officer field is required.',
            // 'chiefinvestigation.required' => 'The chief investigation field is required.'
        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
           try {
            // Convert and overwrite in the same variable
            $request->from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
             $request->to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            } catch (\Exception $e) {
            // Handle invalid format if needed
            // For example, set null or throw error
            $request->from_date = null;
            $request->to_date = null;
                }
        DB::beginTransaction();
        try {
            $zoneCount = count($request->zone);
            for ($i = 0; $i < $zoneCount; $i++) {
                $zoneName = Zone::find($request->zone[$i]);

                $title = $request->name;

                $nameClean = str_replace(" ", "", $request->name);
                $namePart = strtoupper(substr($nameClean, 0, 2));
                $fromDate = Carbon::parse($request->from_date);
                $fromDateFormatted = $fromDate->format("dm");
                $uniqueCode = $namePart . $fromDateFormatted;

                $survey = Survey::find($request->id) ?? new Survey();

                $survey->name = $title;
                $survey->type_id = $request->type;
                $survey->zone_id = $request->zone[$i];
                $survey->start_date = $request->from_date;
                $survey->end_date = $request->to_date;
                $survey->status = $request->status;
                $survey->investigation_officer = $request->investigation;
                $survey->chief_investigation_officer = $request->chiefinvestigation;
                $survey->save();

                $surveyId = $survey->id;
                $uniqueCode = $uniqueCode . $surveyId;

                Survey::where("id", $surveyId)->update([
                    "survey_id" => $uniqueCode,
                ]);

                // Insert Markets
                foreach ($request->market as $marketId) 
                {
                    $market = Market::find($marketId);

                    if ($market && $market->zone_id == $survey->zone_id) 
                    {
                        SurveyMarket::create([
                            'survey_id' => $surveyId,
                            'market_id' => $marketId,
                        ]);
                    }
                }

                // Insert Categories
                foreach ($request->category as $categoryId) {
                    SurveyCategory::create([
                        "survey_id" => $surveyId,
                        "category_id" => $categoryId,
                    ]);
                }

                // Insert Surveyors
                foreach ($request->surveyor as $surveyorId) {
                    SurveySurveyor::create([
                        "survey_id" => $surveyId,
                        "surveyor_id" => $surveyorId,
                    ]);
                    $res = [
                        "surveyId" => $surveyId,
                    ];
                    $res = json_encode($res);
                    $existingNotification = Notification::where(
                        "user_id",
                        $surveyorId
                    )
                        ->where("survey_id", $surveyId)
                        ->first();

                    if (!$existingNotification) {
                        $notifications = [
                            "type" => "assigned_survey",
                            "data" => $res,
                            "title" => "Survey Assigned",
                            "user_id" => $surveyorId,
                            "message" => "You have been assigned a new survey.",
                            "survey_id" => $surveyId,
                        ];
                        Notification::create($notifications);
                    }
                }
            }

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => $request->id
                    ? "Survey updated successfully."
                    : "Survey added successfully.",
            ]);
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction if there’s an error
            return response()->json(
                [
                    "success" => false,
                    "message" => "Something went wrong.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function updateStatus(Request $request)
    {
        $survey = Survey::find($request->id);

        if ($survey) {
            $survey->status = $request->status;

            $survey->save();

            return response()->json([
                "success" => true,
                "message" => "Status updated successfully.",
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong.",
            ]);
        }
    }

    public function edit($id)
    {
        // $data = Commodity::with('category', 'brand', 'uom')->find($id);

        $data = Survey::with(
            "zone",
            "markets",
            "categories",
            "products",
            "surveyors",
            "investigationOfficer",
            "chiefofficer",
            "type"
        )
            ->orderby("id", "desc")
            ->find($id);

        $totalMarkets = Market::select('id', 'name')->where(['zone_id'=>$data->zone_id])->orderby('name', 'asc')->get();

        // List of selected market 
        $markets = SurveyMarket::where("survey_id", @$data->id)
            ->join('markets', 'markets.id', '=', 'survey_markets.market_id')
            ->select('survey_markets.*', 'markets.name')
            ->orderBy('markets.name', 'asc')
            ->get();

        // List of selected category
        $categorys = SurveyCategory::where("survey_id", @$data->id)
            ->join('categories', 'categories.id', '=', 'survey_categories.category_id')
            ->select('survey_categories.*', 'categories.name')
            ->orderBy('categories.name', 'asc')
            ->get();
        $typecategory = Category::where(["status" => "1", "type_id" => $data->type_id])
            ->orderby("name", "asc")
            ->get();

        // List of compilances
        $compilances = SurveySurveyor::where("survey_id", @$data->id)
        ->join('users', 'users.id', '=', 'survey_surveyors.surveyor_id')
        ->select('survey_surveyors.*', 'users.name','users.title')
        ->orderBy('users.name', 'asc')
        ->get();

        if ($data) {
        $data->start_date = customt_date_format($data->start_date);
        $data->end_date = customt_date_format($data->end_date);


            return response()->json([
                "success" => true,
                "data" => $data,
                "market" => $markets,
                "category" => $categorys,
                "typecategory" => $typecategory,
                "compilances" => $compilances,
                'totalMarkets' => $totalMarkets
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No record found.",
            ]);
        }
    }

    public function updateSurvey(Request $request){
        // return $request->all();
        $rules = [
            "name" => "required|max:250",
            "zone" => "required",
            // "investigation" => "required",
            "type" => "required",
            // "chiefinvestigation" => "required",
            "market" => "required|array",
            "category" => "required|array",
            "surveyor" => "required|array",
            "from_date" => "required|date",
            "to_date" => "required|date|after_or_equal:from_date",
            "status" => "required|in:0,1",
        ];

        $messages = [
            'market.required' => 'The store field is required.',
            'surveyor.required' => 'The compliances officer field is required.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
           try {
            // Convert and overwrite in the same variable
            $request->from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
             $request->to_date = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');
            } catch (\Exception $e) {
            // Handle invalid format if needed
            // For example, set null or throw error
            $request->from_date = null;
            $request->to_date = null;
                }
        DB::beginTransaction();

        try {
            if ($request->id) {

                $survey = Survey::updateOrCreate(
                    ["id" => $request->id],
                    [
                        "name" => $request->name,

                        "zone_id" => $request->zone,

                        "start_date" => $request->from_date,

                        "end_date" => $request->to_date,

                        "status" => $request->status,

                        "investigation_officer" => $request->investigation,

                        "type_id" => $request->type,

                        "chief_investigation_officer" => $request->chiefinvestigation,
                    
                    ]
                );

                $surveyId = $survey->id;

                // Update Markets

                DB::enableQueryLog();

                SurveyMarket::/*whereNotIn('market_id', $request->market)->*/ where(
                    "survey_id",
                    $request->id
                )->delete();

                $marketsData = collect($request->market)
                    ->map(
                        fn($marketId) => [
                            "survey_id" => $surveyId,

                            "market_id" => $marketId,
                        ]
                    )
                    ->toArray();

                SurveyMarket::upsert($marketsData, ["survey_id", "market_id"]);

                // Update Categories

                SurveyCategory::/*whereNotIn('category_id', $request->category)->*/ where(
                    "survey_id",
                    $request->id
                )->delete();

                $categoriesData = collect($request->category)
                    ->map(
                        fn($categoryId) => [
                            "survey_id" => $surveyId,

                            "category_id" => $categoryId,
                        ]
                    )
                    ->toArray();

                SurveyCategory::upsert($categoriesData, [
                    "survey_id",
                    "category_id",
                ]);

                // Update Surveyors

                SurveySurveyor::/*whereNotIn('surveyor_id', $request->surveyor)->*/ where(
                    "survey_id",
                    $request->id
                )->delete();

                $surveyorsData = collect($request->surveyor)
                    ->map(
                        fn($surveyorId) => [
                            "survey_id" => $surveyId,

                            "surveyor_id" => $surveyorId,
                        ]
                    )
                    ->toArray();

                SurveySurveyor::upsert($surveyorsData, [
                    "survey_id",
                    "surveyor_id",
                ]);

                DB::commit();

                return response()->json([
                    "success" => true,
                    "message" => "Survey updated successfully.",
                ]);
            } 
            else 
            {
                $survey = Survey::updateOrCreate(
                    ["id" => $request->id],
                    [
                         "name" => $request->name,

                        "zone_id" => $request->zone,

                        "start_date" => $request->from_date,

                        "end_date" => $request->to_date,

                        "status" => $request->status,

                        "type_id" => $request->type,

                        "investigation_officer" => $request->investigation,

                        "chief_investigation_officer" => $request->chiefinvestigation
                    ]
                );

                $surveyId = $survey->id;

                $nameClean = str_replace(" ", "", $request->name);

                $namePart = strtoupper(substr($nameClean, 0, 2));

                $fromDate = Carbon::parse($request->from_date);

                $fromDateFormatted = $fromDate->format("dm");

                $uniqueCode = $namePart . $fromDateFormatted;

                $uniqueCode = $uniqueCode . $surveyId;

                Survey::where("id", $surveyId)->update([
                    "survey_id" => $uniqueCode,
                ]);

                $marketsData = collect($request->market)
                    ->map(
                        fn($marketId) => [
                            "survey_id" => $surveyId,

                            "market_id" => $marketId,
                        ]
                    )
                    ->toArray();

                SurveyMarket::upsert($marketsData, ["survey_id", "market_id"]);

                // Update Categories

                $categoriesData = collect($request->category)
                    ->map(
                        fn($categoryId) => [
                            "survey_id" => $surveyId,

                            "category_id" => $categoryId,
                        ]
                    )
                    ->toArray();

                SurveyCategory::upsert($categoriesData, [
                    "survey_id",
                    "category_id",
                ]);

                // Update Surveyors

                $surveyorsData = collect($request->surveyor)
                    ->map(
                        fn($surveyorId) => [
                            "survey_id" => $surveyId,

                            "surveyor_id" => $surveyorId,
                        ]
                    )
                    ->toArray();

                SurveySurveyor::upsert($surveyorsData, [
                    "survey_id",
                    "surveyor_id",
                ]);

                DB::commit();

                return response()->json([
                    "success" => true,
                    "message" => "Survey Created successfully.",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction if there’s an error

            return response()->json(
                [
                    "success" => false,
                    "message" => "Something went wrong.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $survey = Survey::find($id);
            if ($survey) {
                $submittedSurvey = SubmittedSurvey::where(
                    "survey_id",
                    $id
                )->get();
                if (count($submittedSurvey) > 0) {
                    return response()->json([
                        "success" => false,
                        "message" =>
                            "This survey cannot be deleted because it has already started.",
                    ]);
                }
                SurveyMarket::where("survey_id", $id)->delete();
                SurveyCategory::where("survey_id", $id)->delete();
                SurveySurveyor::where("survey_id", $id)->delete();
                $survey->delete();
                DB::commit(); // Commit transaction if everything is successful
                return response()->json([
                    "success" => true,
                    "message" => "Survey deleted successfully",
                ]);
                // return redirect()->route('admin.survey.list')->withSuccess('Survey deleted successfully!');
            } else {
                DB::rollBack(); // Rollback transaction if survey not found
                // return redirect()->back()->withError('Survey not found.');
                return response()->json([
                    "success" => false,
                    "message" => "Survey not found",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // return redirect()->back()->withError('Something went wrong. Please try later.');
            return response()->json([
                "success" => false,
                "message" => "Something went wrong. Please try later.",
            ]);
        }
    }

    public function exportSurvey(Request $request)
    {
        $filters = $request->only([
            "name",
            "survey_id",
            "zone",
            "category",
            "market",
            "compilance_officer",
            "type",
            "investigation_officer",
            "chief_investigation_officer",
            "status",
            "start_date",
            "end_date",
        ]);

        // return new SurveyExport($filters), 'survey.xlsx');

        return Excel::download(new SurveyExport($filters), "survey.xlsx");
    }
}
