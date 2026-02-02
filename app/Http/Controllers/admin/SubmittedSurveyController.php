<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\SubmittedSurveyExport;

use App\Exports\SubmittedSurveyDetailsExport;

use App\Imports\SubmittedSurveyImport;
use DB;

use Auth;

use App\Events\PublishEvent;

use App\Models\Zone;
use App\Models\User;
use App\Models\UOM;

use App\Models\Brand;

use App\Models\Survey;

use App\Models\Market;

use App\Models\Category;

use App\Models\Commodity;

use App\Models\SubmittedSurvey;

use App\Http\Traits\ImageTrait;

use App\Models\Type;

use App\Models\SurveyMarket;

use App\Models\SurveyCategory;

use App\Models\AmountUpdateLog;



class SubmittedSurveyController extends Controller

{

    use ImageTrait;

    public function __construct (){

        $this->middleware('permission:submit_survey_list', ['only' => ['index']]);

        $this->middleware('permission:submit_survey_create', ['only' => ['create', 'store']]);

        $this->middleware('permission:submit_survey_edit', ['only' => ['edit', 'update', 'changeStatus']]);

        $this->middleware('permission:submit_survey_delete', ['only' => ['delete']]);

    }

    public function index(){
        $title = 'Submitted Survey List';
        $role = Auth::user()->roles['0']->name;
        if(strtolower($role) == 'admin' || strtolower($role) == 'chief investigation officer'){
            $latestIds = SubmittedSurvey::groupBy('survey_id')->selectRaw('MAX(id) as id')->pluck('id');
            $data = SubmittedSurvey::with('zone', 'survey', 'submitter')
                                        ->whereIn('id', $latestIds)->orderBy('id', 'desc')->paginate(10);
            $zone = Zone::where('status', '1')->orderby('id', 'desc')->get();
        }
        else{
            $assignedSuveys = Survey::where('investigation_officer', Auth::id())->pluck('id');
            // dd($assignedSuveys);
            // $latestIds = SubmittedSurvey::groupBy('survey_id')->selectRaw('MAX(id) as id')->pluck('id');
            // $data = SubmittedSurvey::with('zone', 'survey', 'submitter')
            //                             ->whereIn('id', $latestIds)->whereIn('survey_id', $assignedSuveys)->orderBy('id', 'desc')->paginate(10);
            $latestIds = SubmittedSurvey::groupBy('survey_id')->selectRaw('MAX(id) as id')->pluck('id');
            $data = SubmittedSurvey::with('zone', 'survey', 'submitter')
                                        ->whereIn('id', $latestIds)->orderBy('id', 'desc')->paginate(10);
                                        // ->whereIn('id', $latestIds)->orderBy('id', 'desc')->paginate(10);
            $zone = Zone::where('status', '1')->orderby('id', 'desc')->get();
        }
        return view('admin.submittedSurvey.index', compact('title', 'data', 'zone'));
    }

    public function filter(Request $request){
        $title = 'Submitted Survey List';
        // dd($request->all());
        $today = date('Y-m-d');

        $latestIds = SubmittedSurvey::groupBy('survey_id')->selectRaw('MAX(id) as id')->pluck('id');

        $zone = Zone::where('status', '1')->orderby('id', 'desc')->get();

        $query = SubmittedSurvey::with('zone', 'survey', 'submitter');

        if($request->survey_number){

            $survey_id = preg_replace('/[^a-zA-Z0-9]/', '', $request->survey_number);
            // $query->where('survey_number', $request->survey_id);
            $suveryId = Survey::where('survey_id', $survey_id)->pluck('id');
            
            $query->whereIn('survey_id', $suveryId);

        }

        if($request->name){

            $suverys = Survey::where('name', 'like', '%' . $request->name . '%')->pluck('id');

            $query->whereIn('survey_id', $suverys);

        }

        if($request->zone){

            $query->where('zone_id', $request->zone);

        }
        // Overdue
        // if($value->survey){
        //  if($value->survey->is_complete == 1){
        //   echo 'Approved';
        //  }
        //  else if(($value->survey->is_complete == 0) && $value->survey->end_date<$today){
        //   echo 'Overdue';
        //  }else{
        //   echo 'In Progress';
        //  }
        // }
        if(($request->status === '0') || ($request->status === '1') || ($request->status === '2') ){
            if($request->status === '0'){
                $suverys1 = Survey::where('is_complete', $request->status)->whereDate('end_date', '>=', $today)->pluck('id');

                $query->whereIn('survey_id', $suverys1);

            }
            if($request->status === '1'){
                $suverys2 = Survey::where('is_complete', $request->status)->pluck('id');

                $query->whereIn('survey_id', $suverys2);
            }
            if($request->status === '2'){
                $suverys3 = Survey::where('is_complete', '0')->whereDate('end_date', '<', $today)->pluck('id');

                $query->whereIn('survey_id', $suverys3);
            }

        }

        if(($request->is_publish === '0') || ($request->is_publish === '1') ){

            $query->where('publish', $request->is_publish);

        }

        if($request->start_date){

            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));

        }

        if($request->end_date){

            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));

        }



        $data = $query->whereIn('id', $latestIds)->orderBy('id', 'desc')->paginate(10);

        return view('admin.submittedSurvey.index', compact('title', 'data', 'zone'));   

    }



    public function exportSubmittedSurvey(Request $request){
        $filters = $request->only(['survey_number', 'name', 'zone', 'publish', 'status', 'start_date', 'end_date']);
        return Excel::download(new SubmittedSurveyExport($filters), 'submitted-surveys.xlsx');
    }



    public function surveyDetail($id){
        $survey = Survey::with('surveyType')->find($id);
        if($survey){
            // dd($survey);
            $type = ($survey->surveyType)?$survey->surveyType->name:'';
            $path = (strtolower($type) == 'medication')?'medication_details':'details';
            $assignies = User::whereHas('roles', function($query) {
                $query->where('name', 'Compliance Officer');
            })
            ->select('id', 'name', 'title') // Select only needed columns from users table
            ->orderBy('name', 'asc')
            ->get();


            $updaters = User::whereHas('roles', function($query){
                $query->where('name', '!=' ,'Compliance Officer');
            })->orderby('name', 'asc')->get();

            $units = UOM::where('status', '1')->orderby('name', 'asc')->get();
            $brands = Brand::where('status', '1')->orderby('name', 'asc')->get();
            $markets = Market::where(['status'=>'1', 'zone_id'=>$survey->zone_id])->orderby('name', 'asc')->get();
            $categories = Category::where('status', '1')->orderby('name', 'asc')->get();
            $commodities = Commodity::with('uom')->where('status', '1')->orderby('name', 'asc')->get();
            $title = 'Submitted Survey Details';
            $data = SubmittedSurvey::with('user', 'zone', 'survey', 'market', 'category', 'commodity', 'unit', 'brand', 'submitter', 'updater')->where(['survey_id'=>$id, /*'is_submit'=>1*/])->orderby('id', 'desc')->paginate(10);
            
            return view("admin.submittedSurvey.$path", compact('title', 'data', 'id', 'assignies', 'updaters', 'units', 'brands', 'markets', 'categories', 'commodities'));
        }else{
            return back()->withError('Something went wrong');
        }

    }



    public function filterSurveyDetails(Request $request){

        $title = 'Submitted Survey Details';

        $id = $request->sid;

        $survey = Survey::find($id);

         $assignies = User::whereHas('roles', function($query) {
                $query->where('name', 'Compliance Officer');
            })
            ->select('id', 'name', 'title') // Select only needed columns from users table
            ->orderBy('name', 'asc')
            ->get();
// dd($assignies);


        $updaters = User::whereHas('roles', function($query){

            $query->where('name', '!=' ,'surveyors');

        })->orderby('name', 'asc')->get();



        $units = UOM::where('status', '1')->orderby('name', 'asc')->get();

        $brands = Brand::where('status', '1')->orderby('name', 'asc')->get();

        $markets = Market::where(['status'=>'1', 'zone_id'=>$survey->zone_id])->orderby('name', 'asc')->get();

        $categories = Category::where('status', '1')->orderby('name', 'asc')->get();

        $commodities = Commodity::where('status', '1')->orderby('name', 'asc')->get();



        $query = SubmittedSurvey::with('user', 'zone', 'survey', 'market', 'category', 'commodity', 'unit', 'brand', 'submitter', 'updater');



        // $suverys = Survey::where('name', 'like', '%' . $request->name . '%')->pluck('id');

        // $query->whereIn('survey_id', $suverys);



        if($request->commodity){

            $query->where('commodity_id', $request->commodity);

        }

        if($request->amount){

            $query->where('amount', $request->amount);

        }

        if($request->assignee){

            $query->where('user_id', $request->assignee);

        }

        if($request->market){

            $query->where('market_id', $request->market);

        }

        if($request->category){

            $query->where('category_id', $request->category);

        }

        if($request->unit){

            $query->where('unit_id', $request->unit);

        }

        if($request->brand){

            $query->where('brand_id', $request->brand);

        }

        if($request->collected_buy){

            $query->where('submitted_by', $request->collected_buy);

        }

        if($request->updated_by){

            $query->where('updated_by', $request->updated_by);

        }

        if(($request->status === '0') || ($request->status === '1') ){

            $query->where('status', $request->status);

        }

        if($request->availability){
            $query->where('availability', $request->availability);
        }

        if(($request->publish === '0') || ($request->publish === '1') ){
            // dd($request->publish);
            $query->where('publish', $request->publish);
        }

        if($request->collected_date){

            $query->whereDate('created_at', date('Y-m-d', strtotime($request->start_date)));

        }

        if($request->start_date){

            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));

        }

        if($request->end_date){

            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));

        }



        $data = $query->where('survey_id', $id)->orderby('id', 'desc')->paginate(10);

        return view('admin.submittedSurvey.details', compact('title', 'data', 'id', 'assignies', 'updaters', 'units', 'brands', 'markets', 'categories', 'commodities'));

    }



    public function getCategoryCommodity($id){
        $data = Commodity::select('id', 'category_id', 'name')
                        ->where(['category_id'=>$id, 'status'=>'1'])->get();
        if($data){
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function exportSurveyDetails(Request $request, $id){
        $surveyName = Survey::find($id);
        $name = ($surveyName)?ucwords($surveyName->name):'No Name';
        $filters = $request->only(['sid', 'amount', 'assignee', 'commodity', 'availability', 'market', 'category', 'unit', 'brand', 'collected_buy', 'updated_by', 'publish', 'collected_date', 'start_date', 'end_date']);

        return Excel::download(new SubmittedSurveyDetailsExport($filters, $id), "$name.xlsx");
    }

    public function updateStatus($id){
        $data = SubmittedSurvey::find($id);
        if($data){
            // $data->update(['status' => DB::raw('IF(status = 1, 0, 1)')]);
            $result = SubmittedSurvey::where('id', $id)->update(['status'=>'1', 'is_save'=>1, 'is_submit'=>1]);
            if($result){
                return response()->json(['success' => true, 'message'=>'Survey updated successfully.']);
            }else{
                return response()->json(['success' => false, 'message'=>'Something went wrong.']);
            }
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function updateSurveyStatus($id){
        $data = Survey::find($id);
        if($data){
            $res = $data->update(['is_complete' => !$data->is_complete]);
            // $data->update(['is_complete' => DB::raw('IF(is_complete = 1, 0, 1)')]);
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    //INITIALLY IT WAS USED FOR PULISH THESUBMITTED SURVEYS.
    // public function publishSurvey($id){
    //     $data = SubmittedSurvey::find($id);
    //     if($data){
    //         $data->update(['publish' => DB::raw('IF(publish = 1, 0, 1)')]);
    //         return response()->json(['success' => true, 'message'=>'Survey published successfully.']);
    //     }else{
    //         return response()->json(['success' => false, 'message'=>'Something went wrong.']);
    //     }
    // }

    public function editSubmittedSurvey($id){
        $title = 'Update Submitted Survey';
        $data = SubmittedSurvey::with('zone:id,name', 'survey:id,survey_id,name', 'market:id,name', 'category:id,name', 'commodity:id,name,image', 'unit:id,name', 'brand:id,name')
                                ->where(['id'=>$id])->first();
        if($data){
            return view('admin.submittedSurvey.edit', compact('title', 'data'));
        }else{
            return back()->withError('Something went wrong');
        }  
    }
    public function updateSubmittedSurvey(Request $request){
        $submittedSurvey = SubmittedSurvey::find($request->id);
        $this->validate($request, [
            'id' => 'required|integer',
            'price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'availability' => 'nullable|string|in:low,moderate,high',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:' . @$submittedSurvey->created_at
            ],
        ]);
        $data = array(
            'amount' =>$request->price,
            'availability' =>$request->availability,
            'updated_by' =>Auth::id(),
            'status' =>$request->status,
            'publish' =>$request->is_approve,
            'commodity_expiry_date' =>$request->expiry_date,
        );
        if ($request->hasFile('image')) {
            $path = 'submittedSurveyImage/';
            if ($submittedSurvey->commodity_image) {
                $oldImagePath = public_path($path . $submittedSurvey->commodity_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $this->imageUpload($request->file('image'), $path);
            $data['commodity_image'] = $image;
        }

        $response = SubmittedSurvey::where('id', $request->id)->update($data);
        
        if($response){
            return redirect()->route('admin.submitted.survey.details', $request->survey_id)->withSuccess('Submitted Survey updated successfully!');
        }else{
           return back()->withError('Something went wrong');
        }
    }

    public function approveSubmittedSurvey(Request $request){
        $ids = $request->ids;
        if(count($ids)>0){
            $result = SubmittedSurvey::whereIn('id', $ids)->update(['status'=>'1', 'is_save'=>1, 'is_submit'=>1]);
            if($result){
                return response()->json(['success' => true, 'message'=>'Survey updated successfully.']);
            }else{
                return response()->json(['success' => false, 'message'=>'Something went wrong.']);
            }
        }
    }

    public function approveSurvey($id){
        $data = Survey::find($id);
        if($data){
            $result = SubmittedSurvey::where('survey_id', $id)->update(['status'=>'1', 'is_submit'=>1, 'is_save'=>1]);
            if($result){
                Survey::where('id', $id)->update(['is_approve' => '1', 'is_complete'=>1]);
                 // $surveyResult = Survey::whereIn('id', $request->ids)->update(['is_complete'=>1]);
                return response()->json(['success' => true, 'message'=>'Survey approved successfully.']);
            }else{
                return response()->json(['success' => false, 'message'=>'Something went wrong.']);
            }
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function publishSurveys(Request $request){
        $userId = Auth::id();
        $type = 'publish';
        $result = SubmittedSurvey::whereIn('survey_id', $request->ids)->update(['publish'=>'1']);
        // foreach ($request->ids as $id) {
        //     event(new PublishEvent($id, $userId, $type));
        // }
        event(new PublishEvent($request->ids, $userId, $type));
        // $surveyResult = Survey::whereIn('id', $request->ids)->update(['is_complete'=>1]);
        if($result){
            return response()->json(['success' => true, 'message'=>'Survey published successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function unpublish(Request $request){
        $userId = Auth::id();
        $type = 'unpublish';
        $result = SubmittedSurvey::where('survey_id', $request->survey_id)->update(['publish'=>'0']);
        event(new PublishEvent($request->survey_id, $userId, $type));
        if($result){
            return response()->json(['success' => true, 'message'=>'Survey unpublished successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function surveyReportpreview($id){
        $title = 'Survey Report Preview';
        $survey = Survey::with('surveyType')->find($id);
        $type = ($survey->surveyType)?$survey->surveyType->name:'';
        $path = (strtolower($type) == 'medication')?'medication':'preview';
        $zone = Zone::where('id', @$survey->zone_id)->first();

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

        $data = SubmittedSurvey::with(['commodity', 'category', 'market', 'survey', 'zone', 'brand', 'unit'])
            ->where(['survey_id' => $id, /*'is_submit' => 1*/])
            // ->whereIn('survey_id', [79,78])
            ->get()
            ->groupBy('category.name'); 

        $types = Type::orderby('name', 'asc')->get();

        return view("admin.submittedSurvey.$path", compact('title', 'data', 'id', 'markets', 'filterMarkets', 'allCommodities', 'filterCategories', 'zone', 'survey', 'types'));
    }

    public function publishSingleSurvey(Request $request){
        $userId = Auth::id();
        $type = 'publish';
        $result = SubmittedSurvey::where('survey_id', $request->survey_id)->update(['publish'=>'1']);
        $surveyIds = explode(" ", $request->survey_id);
        event(new PublishEvent($surveyIds, $userId, $type));
        if($result){
            return response()->json(['success' => true, 'message'=>'Survey published successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }   
    }

    public function importSurvey(){
        $title = 'Import Survey';
        return view('admin.submittedSurvey.import_survey', compact('title'));
    }

    public function importSurveys(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:10240' //10 MB MAX
        ]);
        try{
            Excel::import(new SubmittedSurveyImport, $request->file('file'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('admin.submitted.survey.list')->withSuccess('Survey Submitted successfully.');
    }

    public function priceLog($id){ 
        $title = 'Price Update Log';
        $data = AmountUpdateLog::with('submittedSurvey.market', 'submittedSurvey.category', 'submittedSurvey.commodity', 'submittedSurvey.unit', 'submittedSurvey.brand', 'updatedBy')->where('survey_id', $id)->orderby('id', 'desc')->paginate(10);
        $survey = Survey::find($id);
        // dd($data);
        return view('admin.submittedSurvey.price_log', compact('title', 'survey', 'data'));
    }



}

