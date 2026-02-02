<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use Illuminate\Support\Carbon;
use App\Events\PublishEvent;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\Zone;
use App\Models\UOM;
use App\Models\Brand;
use App\Models\Survey;
use App\Models\Market;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\SubmittedSurvey;
use App\Models\SurveySurveyor;
use App\Models\SurveyCategory;
use App\Models\SurveyMarket;

use DB;

class SurveyController extends Controller
{

    use ImageTrait;

    /**
     * Get user profile.
     *
     * @author Irfan User
     * @date   24-02-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user who is logging out.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(), 
            [
                'id'=>'required|integer', //User Id
                'is_complete'=>'nullable|integer|in:0,1', //1=complete,0=pending 
                'is_overdue'=>'nullable|integer|in:0,1', //1=OverDue
                'page' => 'nullable|integer',
                'limit' => 'nullable|integer'
            ]);

            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $checkSurveyor = User::where(['id'=>$request->id, 'status'=>'1'])->first();
            
            $role = strtolower($checkSurveyor->roles['0']->name);

            if($checkSurveyor){
            	$today = date('Y-m-d');
	            $surveys = SurveySurveyor::where('surveyor_id', $request->id)->pluck('survey_id');

	            if(($role == 'investigation officer') || ($role == 'chief investigation officer')){
	            	$queryColumn = ($role == 'investigation officer')?'investigation_officer':'chief_investigation_officer';
	            	// return $queryColumn;
	            	$query = Survey::with('zone', 'submittedSurveys', 'surveyType:id,name')->where('status', '1')/*->where('is_complete',0)*/->where("$queryColumn", $request->id);
	            // return $query->get();
	            }else{
	            	$query = Survey::with('zone', 'submittedSurveys', 'surveyType:id,name')->where('status', '1')->where('is_complete','0')->whereIn('id', $surveys);
	            }

	            if(isset($request->is_complete))
	            { 
	                $query->where('is_complete', $request->is_complete)->where('is_complete','0');
	            }

	            if(isset($request->is_overdue))
	            {
	                if($request->is_overdue == true){ // PENDING AND OVERDUE
	                    $query->where('is_overdue', 0)->whereDate('end_date', '<', $today);
	                }
	                if($request->is_overdue == false){ // PENDING BUT NOT OVERDUE
	                    $query->where('is_overdue', 0)->whereDate('end_date', '>', $today);
	                }
	            }

	            $data = $query->orderby('id', 'desc')->get();
	            // dd(DB::getQueryLog());
	            // return $data;
	            if(count($data)>0)
	            {
	            	foreach ($data as $key => $value) 
	            	{	

	            		$endDate = date('Y-m-d', strtotime($value->end_date));
	            		    
	            		    if ($value->is_complete == false && ($endDate < $today)) 
	            		    {
	            		        $value->is_overdue = true;
	            		    } 
	            		    else 
	            		    {
	            		        $value->is_overdue = false;
	            		    }
	            		    
	            		$data[$key]['is_complete'] = ($value->is_complete == 1)?true:false;
	            		// $data[$key]['is_publish'] = $submitedSurveyss;
	            		// $data[$key]['valueId'] = $value->published_submitted_surveys_count;
	            		
	            		// $submitedSurveyss = ($value->published_submitted_surveys_count>0)?'0':'1';
	            		if(count($value->submittedSurveys)>0){
	            			$checkPublishSurvey = SubmittedSurvey::where(['survey_id'=>$value->id, 'publish'=>'0'])->get();
	            			if(count($checkPublishSurvey)>0){
	            				$is_publish = '0';
	            			}else{
	            				$is_publish = '1';
	            			}
	            		}else{
	            			$is_publish = '0';
	            		}

	            		$data[$key]['is_publish'] = $is_publish;

	            	}
	            }

                return apiResponse(true, 'Record found successfully', $data, 200);
            }else{
                return apiResponse(false, 'Invali user id', null, 204);
            }
        }
        catch(\Exception $e)
        {
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id'=>'required|integer', //ID = Survey ID
    		]);
    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}
    		$data = Survey::with(['zone:id,name', 'surveyType:id,name', 'markets', 'categories'/*,'surveyors'*/])
    						->whereHas('zone', function ($query) {
    						    $query->where('status', 1);
    						})
    						->whereHas('markets', function ($query) {
    						    $query->where('status', 1);
    						})
    						->whereHas('categories', function ($query) {
    						    $query->where('status', 1);
    						})
    						->whereHas('surveyors', function ($query) {
    						    $query->where('status', 1);
    						})
    						->where('status', '1')->find($request->id);

    		$units = UOM::select('id', 'name')->where('status', '1')->orderby('name', 'asc')->get();
    		$brands = Brand::select('id', 'name')->where('status', '1')->orderby('name', 'asc')->get();
    		// return $data;
    		if($data){
    			$commodity = Commodity::select('id', 'name', 'brand_id', 'uom_id', 'unit_value', 'category_id')
    									->whereIn('category_id', $data->categories->pluck('category_id'))
    									->orderby('name', 'asc')->where('status', '1')->get();

    			$markets = Market::select('id', 'name')
    									->whereIn('id', $data->markets->pluck('market_id'))
    									->orderby('name', 'asc')->where('status', '1')->get();

    			$categories = Category::select('id', 'name')
    									->with(['commodities' => function ($query) {
        $query->where('status', 1)->with(['brand', 'uom']);
    }])
    									// ->with(['commodities.brand', 'commodities.uom'])
    									->whereIn('id', $data->categories->pluck('category_id'))
    									->orderby('name', 'asc')->where('status', '1')->get();

    			// $surveyors = User::select('id', 'name')
    			// 						->whereIn('id', $data->surveyors->pluck('surveyor_id'))
    			// 						->orderby('name', 'asc')->where('status', '1')->get();

    			// $data->commodities = $commodity;
    			$data->setRelation('markets', $markets);
    			$data->setRelation('categories', $categories);
    			// $data->setRelation('surveyors', $surveyors);
    			// $data->units = $units;
    			// $data->brands = $brands;
    			$markets = SurveyMarket::whereHas('surveyMarketss')->where('survey_id', $request->id)->get();
    			$categoriesss = SurveyCategory::whereHas('surveyCategoriesss')->where('survey_id', $request->id)->get();
    												
    			$markets = (count($markets)>0)?count($markets):'1';
    			$categoriesss = (count($categoriesss)>0)?count($categoriesss):'1';
    			// $submittedCategory = SubmittedSurvey::where(['survey_id'=>$request->id, 'is_submit'=>1])->groupBy('category_id')->pluck('id');
    			// DB::enableQueryLog();
    			$submittedCategory = SubmittedSurvey::selectRaw('MIN(category_id) as category_id')
    			    ->where(['survey_id' => $request->id, 'is_submit' => 1])
    			    ->groupBy('category_id')
    			    ->pluck('category_id');
    			$submittedCategory = count($submittedCategory);
    			// dd(DB::getQueryLog());
    			    // return ($submittedCategory);

    			$submittedMarkets = SubmittedSurvey::selectRaw('MIN(market_id) as market_id')
    			    ->where(['survey_id' => $request->id, 'is_submit' => 1])
    			    ->groupBy('market_id')
    			    ->pluck('market_id');
    			$submittedMarkets = count($submittedMarkets);

    			$totalCategories = $markets*$categoriesss;
    			// $submittedCategories = $submittedMarkets*$submittedCategory;

    			$submittedCategories = DB::table('submitted_surveys')
    			    ->select('market_id', 'category_id')
    			    ->where('survey_id', $request->id)
    			    ->groupBy('market_id', 'category_id')
    			    ->havingRaw('SUM(CASE WHEN is_submit = 0 THEN 1 ELSE 0 END) = 0')
    			    ->get()
    			    ->count();
    			    
    			$data['total_category'] = $totalCategories;
    			$data['submitted_category'] = $submittedCategories;


    			return apiResponse(true, 'Record found successfully.', $data, 200);
    		}else{
    			return apiResponse(false, 'Invalid survey Id', null, 404);
    		}
    	}catch(\Exception $e){
    		return $e->getMessage();
    	}
    }

    // THIS IS OLD FUNCTION FOR VALIDATION SURVEY FORM DATA
    // public function validateSurvey(Request $request){
    // 	try {
	   //      $validation = Validator::make($request->all(), [
	   //          'zone_id' => 'required|integer',
	   //          'survey_id' => 'required|integer',
	   //          'market_id' => 'required|integer',
	   //          'category_id' => 'required|integer',
	   //          'commodity_id' => 'required|integer',
	   //          'unit_id' => 'required|integer',
	   //          'brand_id' => 'required|integer',
	   //      ]);

	   //      if ($validation->fails()) {
	   //          return response()->json([
	   //              'success' => false,
	   //              'message' => 'Validation error',
	   //              'errors' => $validation->errors()
	   //          ], 422);
	   //      }

	   //      $existingSurvey = SubmittedSurvey::where([
	   //          'zone_id' => $request->zone_id,
	   //          'survey_id' => $request->survey_id,
	   //          'market_id' => $request->market_id,
	   //          'category_id' => $request->category_id,
	   //          'commodity_id' => $request->commodity_id,
	   //          'unit_id' => $request->unit_id,
	   //          'brand_id' => $request->brand_id,
	   //      ])->exists();

	   //      if ($existingSurvey) {
	   //      	return apiResponse(false, 'A survey with the selected values has already been completed.', null, 422);
	   //      }
	   //      return apiResponse(true, 'Survey validation passed. You can proceed with submission.', null, 200);

	   //  } catch (\Exception $e) {
	   //  	return apiResponse(false, $e->getMessage(), null, 500);
	   //  }
    // }

    public function validateSurvey(Request $request){
    	try {
	        $validation = Validator::make($request->all(), [
	            'zone_id' => 'required|integer',
	            'survey_id' => 'required|integer',
	            'market_id' => 'required|integer',
	            'category_id' => 'required|integer',
	            // 'commodity_id' => 'required|integer',
	            // 'unit_id' => 'required|integer',
	            // 'brand_id' => 'required|integer',
	        ]);

	        if ($validation->fails()) {
	            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validation->errors() ], 422);
	        }

	        // $existingSurvey = SubmittedSurvey::where([
	        //     'zone_id' => $request->zone_id,
	        //     'survey_id' => $request->survey_id,
	        //     'market_id' => $request->market_id,
	        //     'category_id' => $request->category_id,
	        //     'commodity_id' => $request->commodity_id,
	        //     'unit_id' => $request->unit_id,
	        //     'brand_id' => $request->brand_id,
	        // ])->exists();

	        $existingSurvey = SubmittedSurvey::with('zone', 'survey', 'survey.surveyType', 'market', 'category', 'unit', 'brand', 'commodity.brand', 'commodity.uom')->where([
	            'zone_id' => $request->zone_id,
	            'survey_id' => $request->survey_id,
	            'market_id' => $request->market_id,
	            'category_id' => $request->category_id,
	        ])->get();
	        // return $existingSurvey;
	        if (count($existingSurvey)>0) {
	        	$ids = $existingSurvey->pluck('commodity_id');
	        	
	        	$commodity = Commodity::with('brand', 'uom')->where(['category_id'=>$request->category_id, 'status'=>'1'])->whereNotIn('id', $ids)->orderby('id', 'desc')->get();

	        	// $existingSurvey = $existingSurvey->merge($commodity);
	        	$existingSurvey = $existingSurvey->concat($commodity);
	        	foreach ($commodity as $ckey => $commodityValue) {
	        		$commodityValue->commodity = clone $commodityValue;
	        		$commodityValue->is_save = false;
	        		$commodityValue->is_submit = false;
	        		$commodityValue->user_id = null;
	        		$commodityValue->zone_id = null;
	        		$commodityValue->survey_id = null;
	        		$commodityValue->market_id = null;
	        		$commodityValue->commodity_id = $commodityValue->id;
	        		$commodityValue->unit_id = null;
	        		$commodityValue->amount = null;
	        		$commodityValue->availability = null;
	        		$commodityValue->commodity_image = null;
	        		$commodityValue->submitted_by = null;
	        		$commodityValue->updated_by = null;
	        		$commodityValue->status = null;
	        		$commodityValue->publish = null;
	        		$commodityValue->commodity_expiry_date = null;
	        		$commodityValue->zone = null;
	        		$commodityValue->survey = null;
	        		$commodityValue->market = null;
	        		$commodityValue->category = null;
	        		$commodityValue->unit = $commodityValue->uom;
	        		$commodityValue->brand = null;
	        	}
	        	// return $existingSurvey;
	        	foreach ($existingSurvey as $key => $value) {
	        		$value->survey_type = ($value->survey)?$value->survey->type:null;
	        		$value->is_save = ($value->is_save == 1)?true:false;
	        		$value->is_submit = ($value->is_submit == 1)?true:false;
	        		if($value->commodity_image){
	        			$value->commodity_image = url('submittedSurveyImage/').'/'.$value->commodity_image;
	        		}
	        		if($value->updated_by){
	        			// $value->submittedBy = "this is in updated by";
	        			$value->submittedBy = User::find(@$value->updated_by);
	        		}else{
	        			// $value->submittedBy = "this is in user";
	        			$value->submittedBy = User::find(@$value->user_id);
	        		}
	        		$value->amount = ($value->amount>0)?$value->amount:null;
	        	}
	        	return apiResponse(true, 'Saved Survey with this category.', $existingSurvey, 200);
	        }else{
		        return apiResponse(false, 'No Survey saved with this category.', null, 404);
	        }
	    } catch (\Exception $e) {
	    	return apiResponse(false, $e->getMessage(), null, 500);
	    }
    }

    // public function submitSurveyOld(Request $request){
    		        // $validation = Validator::make($request->all(), [
    		        //     'user_id' => 'required|integer',
    		        //     'zone_id' => 'required|integer',
    		        //     'survey_id' => 'required|integer',
    		        //     'market_id' => 'required|integer',
    		        //     'category_id' => 'required|integer',
    		        //     'commodity_id' => 'required|array', // Ensure it's an array
    		        //     'commodity_id.*' => 'required|integer', // Validate each value as an integer
    		        //     'unit_id' => 'required|array',
    		        //     'unit_id.*' => 'required|integer',
    		        //     'brand_id' => 'required|array',
    		        //     'brand_id.*' => 'required|integer',
    		        //     'amount' => 'required|array',
    		        //     'amount.*' => 'required|regex:/^\d+(\.\d{1,2})?$/', // Validate each amount value
    		        //     'submitted_by' => 'required|integer',
    		        //     'availability' => 'required|array',
    		        //     'availability.*' => 'required|string|in:low,moderate,high', // Validate each availability value
    		        //     // 'commodity_expiry_date' => Carbon::createFromFormat('d-m-Y', $request->commodity_expiry_date)->format('Y-m-d')
    		        //     'commodity_expiry_date.*' => 'required|date|after_or_equal:today',
    		        //     'commodity_image.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
    		        // ]);

    		        // if ($validation->fails()) {
    		        //     return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		        // }

    		        // $existingSurvey = SubmittedSurvey::where([
    		        //     'zone_id' => $request->zone_id,
    		        //     'survey_id' => $request->survey_id,
    		        //     'market_id' => $request->market_id,
    		        //     'category_id' => $request->category_id,
    		        //     'commodity_id' => $request->commodity_id,
    		        //     'unit_id' => $request->unit_id,
    		        //     'brand_id' => $request->brand_id,
    		        // ])->exists();

    		        // if ($existingSurvey) {
    		        //     return apiResponse(false, 'A survey for this commodity has already been completed.', null, 422);
    		        // }

    		        // $data = $request->only([
    		        //     'user_id', 'zone_id', 'survey_id', 'market_id','category_id', 'commodity_id', 'unit_id', 'brand_id', 'amount', 'submitted_by', 'availability', 'commodity_expiry_date'
    		        // ]);

    		        // if ($request->hasFile('commodity_image')) {
    		        //     $path = 'submittedSurveyImage/';
    		        //     $image = $this->imageUpload($request->file('commodity_image'), $path);
    		        //     $data['commodity_image'] = $image;
    		        // }

    		        // $response = SubmittedSurvey::create($data);

    	            // for ($i=0; $i <count($request->commodity_id) ; $i++) { 
    		           //  $existingSurvey = SubmittedSurvey::where([
    		           //      'zone_id' => $request->zone_id,
    		           //      'survey_id' => $request->survey_id,
    		           //      'market_id' => $request->market_id,
    		           //      'category_id' => $request->category_id,
    		           //      'commodity_id' => $request->commodity_id[$i],
    		           //      'unit_id' => $request->unit_id[$i],
    		           //      'brand_id' => $request->brand_id[$i],
    		           //      'is_submit' => 1,
    		           //  ])->exists();

    		           //  if ($existingSurvey) {
    		           //  	$cId = $request->commodity_id[$i];
    		           //      return apiResponse(false, "A survey for this commodity Id $cId has already been completed.", null, 422);
    		           //  }
    	            // 	$data = array(
    	            // 		'user_id' => $request->user_id,
    	            // 		'zone_id' => $request->zone_id,
    	            // 		'survey_id' => $request->survey_id,
    	            // 		'market_id' => $request->market_id,
    	            // 		'category_id' => $request->category_id,
    	            // 		'submitted_by' => $request->user_id,
    	            // 		'is_submit' => true,
    	            // 		'commodity_id' => $request->commodity_id[$i],
    	            // 		'unit_id' => $request->unit_id[$i],
    	            // 		'brand_id' => $request->brand_id[$i],
    	            // 		'amount' => $request->amount[$i],
    	            // 		'availability' => $request->availability[$i],
    	            // 		'commodity_expiry_date' => date('Y-m-d', strtotime($request->commodity_expiry_date[$i])),
    	            // 	);

    	            // 	if ($request->hasFile('commodity_image') && isset($request->file('commodity_image')[$i])) {
    	    	       //      $path = 'submittedSurveyImage/';
    	    	       //      $image = $this->imageUpload($request->file('commodity_image')[$i], $path);
    	    	       //      $data['commodity_image'] = $image;
    	    	       //  }
    	            // 	$response = SubmittedSurvey::create($data);
    	            // }
    // }
    public function submitSurvey(Request $request){
    	try {
    		// return $request->all();
	        // $validation = Validator::make($request->all(), [
	        //     'user_id' => 'required|integer',
	        //     'zone_id' => 'required|integer',
	        //     'survey_id' => 'required|integer',
	        //     'market_id' => 'required|integer',
	        //     'category_id' => 'required|integer',
	        //     'commodity_id' => 'required|array', 
	        //     'commodity_id.*' => 'required|integer', 
	        //     'unit_id' => 'required|array',
	        //     'unit_id.*' => 'required|integer',
	        //     'brand_id' => 'required|array',
	        //     'brand_id.*' => 'required|integer',
	        //     // 'amount' => 'required|array',
	        //     // 'amount.*' => 'required',
	        //     // 'amount.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
	        //     'submitted_by' => 'required|integer',
	        //     'availability' => 'required|array',
	        //     'availability.*' => 'required|string|in:low,moderate,high',
	        //     // 'commodity_expiry_date.*' => 'required|date|after_or_equal:today',
	        //     'commodity_image.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
	        // 	'submited_survey_id'=> 'required|array',
	        // 	'submited_survey_id.*'=> 'required|integer',
	        // ]);

	        // if ($validation->fails()) {
	        //     return apiResponse(false, 'Validation error', $validation->errors(), 422);
	        // }

            $existingSurveys = SubmittedSurvey::where('zone_id', $request->zone_id)
                ->where('survey_id', $request->survey_id)
                ->where('market_id', $request->market_id)
                ->where('category_id', $request->category_id)
                ->where('is_submit', 1)
                ->pluck('commodity_id')
                ->toArray();
            // return $existingSurveys;
            $surveyData = [];
            if(isset($request->commodity_id) && count($request->commodity_id)>0){
            	foreach ($request->commodity_id as $i => $commodityId) {
            	    if (in_array($commodityId, $existingSurveys)) {
            	        return apiResponse(false, "A survey for this commodity ID $commodityId has already been completed.", null, 422);
            	    }

            	    $updateData = [
            	        'user_id' => $request->user_id,
            	        'zone_id' => $request->zone_id,
            	        'survey_id' => $request->survey_id,
            	        'market_id' => $request->market_id,
            	        'category_id' => $request->category_id,
            	        'submitted_by' => $request->user_id,
            	        'is_submit' => true,
            	        'commodity_id' => $commodityId,
            	        'unit_id' => $request->unit_id[$i] ?? null,
            	        'brand_id' => $request->brand_id[$i] ?? null,
            	        'amount' => ($request->amount[$i])?($request->amount[$i]>'0')?$request->amount[$i]:null:null  ?? null,
            	        'amount_1' => ($request->amount_1[$i])?($request->amount_1[$i]>'0')?$request->amount_1[$i]:null:null  ?? null,
            	        // 'amount' => $request->amount[$i] ?? null,
            	        'availability' => $request->availability[$i] ?? null,
            	        'commodity_expiry_date' => isset($request->commodity_expiry_date[$i])
            	            ? $request->commodity_expiry_date[$i]
            	            : null,
            	    ];
            	    
            	    // Handle image upload if provided
            	    if ($request->hasFile("commodity_image.$i")) {
            	        $path = 'submittedSurveyImage/';
            	        $updateData['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path)??null;
            	       
            	    }else{
        	    		$old = SubmittedSurvey::where('id', $request->submited_survey_id[$i])->first();

        	    		$updateData['commodity_image'] = @$old->commodity_image??null;
            	    }

            	    // Update record based on submitted_survey_id
            	    if($request->submited_survey_id[$i]){
            	    // if (!isset($request->submited_survey_id[$i]) || empty($request->submited_survey_id[$i])){
            	    	SubmittedSurvey::where('id', $request->submited_survey_id[$i] ?? null)->update($updateData);
            	    }else{
            	    	$updateData['is_save'] = true;
            	    	SubmittedSurvey::create($updateData);
            	    	// return apiResponse(false, "No commodity has been saved yet.", null, 404);
            	    }
            	    // return $request->submited_survey_id[$i];
            	}

            	return apiResponse(true, "Survey submitted successfully.", null, 200);
            }else{
            	return apiResponse(false, "No commodity have been saved yet.", null, 404);	
            }
            
	    } catch (\Exception $e) {
	        return apiResponse(false, $e->getMessage(), null, 500);
	    }
    }

    // public function submittedSurveyList(Request $request){
    // 	try{
    // 		$validation = Validator::make($request->all(), [
    // 			'id' => 'required|integer',  // USER ID (SURVEYOR)
    // 		]);

    // 		if($validation->fails()){
    // 			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    // 		}

    // 		// $data = SubmittedSurvey::with('zone:id,name', 'survey:id,survey_id,name', 'market:id,name', 'category:id,name', 'commodity:id,name', 'unit:id,name', 'brand')->where('submitted_by', $request->id)->orWhere('updated_by', $request->id)
    // 		// ->orderBy('id', 'desc')->get();
    // 		$query = SubmittedSurvey::with('zone:id,name', 'survey:id,survey_id,name', 'market:id,name', 'category:id,name', 'commodity:id,name,image', 'unit:id,name', 'brand');

    // 		if($request->name){
    // 			$commodities = Commodity::where('name', 'like', '%' . $request->name . '%')->pluck('id');
    // 			$query->whereIn('commodity_id', $commodities);
    // 		}
    // 		if($request->zone_id){
    // 			$query->where('zone_id', $request->zone_id);
    // 		}
    // 		if($request->market_id){
    // 			$query->where('market_id', $request->market_id);
    // 		}
    // 		if($request->category_id){
    // 			$query->where('category_id', $request->category_id);
    // 		}

    // 		if($request->start_date){
    // 			$query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
    // 		}
    // 		if($request->end_date){
    // 			$query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
    // 		}

    // 		$data = $query->where('submitted_by', $request->id)->orWhere('updated_by', $request->id)
    // 		    		->orderBy('id', 'desc')->get();

    // 		if(count($data)>0){
    // 			foreach ($data as $key => $value) {
    // 				if (!empty($value->commodity_image)) {
				//         if (!str_starts_with($value->commodity_image, url('submittedSurveyImage/'))) {
				//             $value['commodity_image'] = url('submittedSurveyImage/' . $value->commodity_image);
				//         }
				//     } 
    // 			}
    			
    // 			return apiResponse(true, 'Record found successfully.', $data, 200);
    // 		}else{
    // 			return apiResponse(false, 'No Record found', null, 404);
    // 		}
    // 	}catch(\Exception $e){
    // 		return apiResponse(false, $e->getMessage(), null, 500);
    // 	}
    // }

    public function submittedSurveyList(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id' => 'required|integer',  // USER ID (SURVEYOR)
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		// $query = SubmittedSurvey::with('zone:id,name', 'survey:id,survey_id,name', 'market:id,name', 'category:id,name', 'commodity:id,name,image', 'unit:id,name', 'brand');
    		$data = Survey::whereHas('surveyors', function($query) use ($request){
    			$query->where('surveyor_id', $request->id);
    		})
    		->whereHas('submittedSurveys', function($query1){
    			$query1->where(['is_submit'=>1, 'is_save'=>1]);
    		})->with('surveyType')->orderby('id', 'desc')->get();

    		foreach($data as $key=> $value){
    			$value->type = ($value->surveyType)?strtolower($value->surveyType->name):'';
    		}
    		if(count($data)>0){
    			return apiResponse(true, 'Record found successfully.', $data, 200);
    		}else{
    			return apiResponse(false, 'No Record found', null, 404);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function submittedSurveyCommodities(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id' => 'required|integer',  // SURVEY ID (SURVEYOR)
    		]);

    		$surveyId = $request->id;

    		$survey = Survey::with([
    			'zone'
    		])
    		->where('id', $surveyId)
    		->first();

    		if (!$survey) {
    			return apiResponse(false, 'Invalid Survey Id.', null, 404);
    		}

    		$groupedData = collect($survey->submittedSurveys)
    			->where('is_submit', 1)
    		    ->groupBy('market_id')
    		    ->map(function ($marketGroup) {
    		        return [
    		            'market_name' => $marketGroup->first()->market->name ?? '',
    		            'categories' => $marketGroup->groupBy('category_id')
    		                ->map(function ($categoryGroup) {
			                    return [
			                        'category_name' => $categoryGroup->first()->category->name ?? '',
	                                'surveys' => $categoryGroup->map(function ($survey) {
	                                    return array_merge(
	                                        // $survey->toArray(),
	                                        // [
	                                        //     'commodity' => $survey->commodity ? $survey->commodity->toArray() : null
	                                        // ]
	                                        $survey->toArray(),
	                                        [
	                                            'commodity' => $survey->commodity ? array_merge(
	                                                $survey->commodity->toArray(),
	                                                [
	                                                    'uom' => $survey->commodity->uom ? $survey->commodity->uom->toArray() : null,
	                                                    'brand' => $survey->commodity->brand ? $survey->commodity->brand->toArray() : null,
	                                                ]
	                                            ) : null
	                                        ]
	                                    );
	                                })->values()
			                    ];
			                })
		                	->values()
    		        ];
    		    })
    		    ->values();

    		// $groupedData = collect($survey->submittedSurveys)
    		//     ->where('is_save', 1)
    		//     ->where('is_submit', 0)
    		//     ->groupBy('market_id')
    		//     ->map(function ($marketGroup) {
    		//         return [
    		//             'market_name' => $marketGroup->first()->market->name ?? '',
    		//             'categories' => $marketGroup->groupBy('category_id')
    		//                 ->map(function ($categoryGroup) {
    		//                     return [
    		//                         'category_name' => $categoryGroup->first()->category->name ?? '',
    		//                         'surveys' => $categoryGroup->map(function ($survey) {
    		//                             return array_merge(
    		//                                 $survey->toArray(),
    		//                                 [
    		//                                     'commodity' => $survey->commodity ? array_merge(
    		//                                         $survey->commodity->toArray(),
    		//                                         [
    		//                                             'uom' => $survey->commodity->uom ? $survey->commodity->uom->toArray() : null,
    		//                                             'brand' => $survey->commodity->brand ? $survey->commodity->brand->toArray() : null,
    		//                                         ]
    		//                                     ) : null
    		//                                 ]
    		//                             );
    		//                         })->values()
    		//                     ];
    		//                 })
    		//                 ->values()
    		//         ];
    		//     })
    		//     ->values();

    		// $groupedData = collect($survey->submittedSurveys)
    		//     // ->where('is_save', 0)
    		//     ->where('is_submit', 1)
    		//     ->groupBy('market_id')
    		//     ->map(function ($marketGroup) {
    		//         return [
    		//             'market_name' => $marketGroup->first()->market->name ?? '',
    		//             'categories' => $marketGroup->groupBy('category_id')
    		//                 ->map(function ($categoryGroup) {
    		//                     return [
    		//                         'category_name' => $categoryGroup->first()->category->name ?? '',
    		//                         'surveys' => $categoryGroup->map(function ($survey) {
    		//                             return array_merge(
    		//                                 $survey->toArray(),
    		//                                 [
    		//                                     'commodity' => $survey->commodity ? array_merge(
    		//                                         $survey->commodity->toArray(),
    		//                                         [
    		//                                             'uom' => $survey->commodity->uom ? $survey->commodity->uom->toArray() : null,
    		//                                             'brand' => $survey->commodity->brand ? $survey->commodity->brand->toArray() : null,
    		//                                         ]
    		//                                     ) : null
    		//                                 ]
    		//                             );
    		//                         })->values()
    		//                     ];
    		//                 })
    		//                 ->values()
    		//         ];
    		//     })
    		//     ->values();



    		$survey = $survey->toArray();
    		unset($survey['submitted_surveys']);

    		$survey['markets'] = $groupedData;

    		if(count($survey)>0){
	    		return apiResponse(true, 'Record found successfully.', $survey, 200);
    		}else{
    			return apiResponse(false, 'No Record found', null, 404);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function savedSurveyCommodities(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id' => 'required|integer',  // SURVEY ID 
    		]);

    		$surveyId = $request->id;

    		$survey = Survey::with([
    			'zone'
    		])
    		->where('id', $surveyId)
    		->first();

    		if (!$survey) {
    			return apiResponse(false, 'Invalid Survey Id.', null, 404);
    		}
    		// $groupedData = collect($survey->submittedSurveys)
    		// 	// ->where(['is_save'=>1, 'is_submit'=>0])
    		// 	->where('is_save', 1)
   			//     ->where('is_submit', 0)
    		//     ->groupBy('market_id')
    		//     ->map(function ($marketGroup) {
    		//         return [
    		//             'market_name' => $marketGroup->first()->market->name ?? '',
    		//             'categories' => $marketGroup->groupBy('category_id')
    		//                 ->map(function ($categoryGroup) {
			   //                  return [
			   //                      'category_name' => $categoryGroup->first()->category->name ?? '',
	     //                            'surveys' => $categoryGroup->map(function ($survey) {
	     //                                return array_merge(
	     //                                    $survey->toArray(),
	     //                                    [
	     //                                        'commodity' => $survey->commodity ? $survey->commodity->toArray() : null
	     //                                    ]
	     //                                );
	     //                            })->values()
			   //                  ];
			   //              })
		    //             	->values()
    		//         ];
    		//     })
    		//     ->values();

    		$groupedData = collect($survey->submittedSurveys)
    		    ->where('is_save', 1)
    		    ->where('is_submit', 0)
    		    ->groupBy('market_id')
    		    ->map(function ($marketGroup) {
    		        return [
    		            'market_name' => $marketGroup->first()->market->name ?? '',
    		            'categories' => $marketGroup->groupBy('category_id')
    		                ->map(function ($categoryGroup) {
    		                    return [
    		                        'category_name' => $categoryGroup->first()->category->name ?? '',
    		                        'surveys' => $categoryGroup->map(function ($survey) {
    		                            return array_merge(
    		                                $survey->toArray(),
    		                                [
    		                                    'commodity' => $survey->commodity ? array_merge(
    		                                        $survey->commodity->toArray(),
    		                                        [
    		                                            'uom' => $survey->commodity->uom ? $survey->commodity->uom->toArray() : null,
    		                                            'brand' => $survey->commodity->brand ? $survey->commodity->brand->toArray() : null,
    		                                        ]
    		                                    ) : null
    		                                ]
    		                            );
    		                        })->values()
    		                    ];
    		                })
    		                ->values()
    		        ];
    		    })
    		    ->values();



    		$survey = $survey->toArray();
    		unset($survey['submitted_surveys']);

    		$survey['markets'] = $groupedData;

    		if(count($survey)>0){
	    		return apiResponse(true, 'Record found successfully.', $survey, 200);
    		}else{
    			return apiResponse(false, 'No Record found', null, 404);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function singleSubmittedSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'user_id' => 'required|integer',
    			'submited_survey_id'=> 'required|integer',
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$data = SubmittedSurvey::with('zone:id,name', 'survey:id,survey_id,name', 'market:id,name', 'category:id,name', 'commodity:id,name,image', 'unit:id,name', 'brand:id,name')
    								->where(['submitted_by'=>$request->user_id, 'id'=>$request->submited_survey_id])
    								->orWhere(['updated_by'=>$request->user_id, 'id'=>$request->submited_survey_id])->first();

    		if($data){
    			$data->commodity_image = url('submittedSurveyImage/' . $data->commodity_image);
    			return apiResponse(true, 'Record found successfully.', $data, 200);
    		}else{
    			return apiResponse(false, 'No Record found', null, 404);
    		}
    	}catch(\Exception $r){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function updateSurvey(Request $request){
    	try{
    		$submittedSurvey = SubmittedSurvey::find($request->submited_survey_id);
    		$validation = Validator::make($request->all(), [
    		    'submited_survey_id' => 'required|integer',
    		    'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    		    'updated_by' => 'required|integer',
    		    'availability' => 'required|string|in:low,moderate,high',
    		    'commodity_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
    		    'commodity_expiry_date' => [
				    'nullable',
				    'date',
				    'before_or_equal:' . @$submittedSurvey->created_at
				],
    		]);

    		if ($validation->fails()) {
    		    return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$data = array(
    			'amount' =>$request->amount,
    			'availability' =>$request->availability,
    			'updated_by' =>$request->updated_by,
    			'commodity_expiry_date' =>$request->commodity_expiry_date,
    		);
    		if ($request->hasFile('commodity_image')) {
    		    $path = 'submittedSurveyImage/';
    		    if ($submittedSurvey->commodity_image) {
    		        $oldImagePath = public_path($path . $submittedSurvey->commodity_image);
    		        if (file_exists($oldImagePath)) {
    		            unlink($oldImagePath);
    		        }
    		    }
    		    $image = $this->imageUpload($request->file('commodity_image'), $path);
    		    $data['commodity_image'] = $image;
    		}

    		$response = SubmittedSurvey::where('id', $request->submited_survey_id)->update($data);
    		if($response){
    			return apiResponse(true, 'Submitted Survey updated successfully!', null, 200);
    		}else{
    			return apiResponse(false, 'something went wrong', null, 500);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function getMasterData(){
    	try{
    		$zones = Zone::with(['markets'=>function($query){
    			$query->where('status', '1');
    		}])->where('status', '1')->get();
    		$categories = Category::where('status', '1')->get();
    		$data['zones'] = $zones;
    		$data['categories'] = $categories;
    		return apiResponse(true, 'Record found successfully.', $data, 200);
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage, null, 500);
    	}
    }

    public function deleteSubmittedSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'user_id' => 'required|integer',
    			'submited_survey_id'=> 'required|integer',
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}
    		$survey = SubmittedSurvey::where(['submitted_by'=>$request->user_id, 'id'=>$request->submited_survey_id])->first();
    		if($survey){
    			$response = SubmittedSurvey::where(['submitted_by'=>$request->user_id, 'id'=>$request->submited_survey_id])->delete();
    			if($response){
    				return apiResponse(true, 'Submitted Survey has been deleted successfully', null, 200);
    			}else{
    				return apiResponse(false, 'Something went wrong.', null, 500);
    			}
    		}else{
    			return apiResponse(false, 'Invalid User ID Survey Id.', null, 404);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function saveSurvey(Request $request){
    	try{
    		// return $request->file('commodity_image');
    		// return $request->all();
    		// $validation = Validator::make($request->all(), [
    		// 	'user_id' => 'required|integer',
    		// 	'zone_id' => 'required|integer',
    		// 	'survey_id' => 'required|integer',
    		// 	'market_id' => 'required|integer',
    		// 	'category_id' => 'required|integer',
    		// 	'commodity_id' => 'required|array', // Ensure it's an array
    		// 	'commodity_id.*' => 'required|integer', // Validate each value as an integer
    		// 	'unit_id' => 'required|array',
    		// 	'unit_id.*' => 'required|integer',
    		// 	'brand_id' => 'required|array',
    		// 	'brand_id.*' => 'required|integer',
    		// 	'amount' => 'required|array',
    		// 	'amount.*' => 'required|regex:/^\d+(\.\d{1,2})?$/', // Validate each amount value
    		// 	'submitted_by' => 'required|integer',
    		// 	'availability' => 'required|array',
    		// 	'availability.*' => 'required|string|in:low,moderate,high',
    		// 	'commodity_expiry_date' => 'required|array',
    		// 	'commodity_expiry_date.*' => 'required|date|after_or_equal:today',
    		// 	'commodity_image' => 'required|array',
    		// 	'commodity_image.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
    		// ]);
    		// if($validation->fails()){
    		//     return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		// }

    		$existingSurveys = SubmittedSurvey::where('zone_id', $request->zone_id)
    		    ->where('survey_id', $request->survey_id)
    		    ->where('market_id', $request->market_id)
    		    ->where('category_id', $request->category_id)
    		    ->where('is_save', 1)
    		    ->pluck('commodity_id')
    		    ->toArray();

    		$surveyData = [];
    		// return count($request->commodity_id);
    		if(isset($request->commodity_id) && count($request->commodity_id)>0){
    			foreach ($request->commodity_id as $i => $commodityId) {
    			    // Prepare the data for create/update
    			    $data = [
    			        'user_id' => $request->user_id,
    			        'zone_id' => $request->zone_id,
    			        'survey_id' => $request->survey_id,
    			        'market_id' => $request->market_id,
    			        'category_id' => $request->category_id,
    			        'submitted_by' => $request->user_id,
    			        'is_submit' => false,
    			        'is_save' => true,
    			        'unit_id' => $request->unit_id[$i] ?? null,
    			        'brand_id' => $request->brand_id[$i] ?? null,
    			        'amount' => ($request->amount[$i])?($request->amount[$i]>'0')?$request->amount[$i]:null:null  ?? null,
    			        'amount_1' => ($request->amount_1[$i])?($request->amount_1[$i]>'0')?$request->amount_1[$i]:null:null  ?? null,
    			        // 'amount' => $request->amount[$i] ?? null,
    			        // 'commodity_image' => "Test Image",
    			        'availability' => $request->availability[$i] ?? null,
    			        'commodity_expiry_date' => isset($request->commodity_expiry_date[$i]) 
    			            ? $request->commodity_expiry_date[$i] 
    			            : null,
    			        // 'commodity_expiry_date' => isset($request->commodity_expiry_date[$i])
            	        //     ? date('Y-m-d', strtotime($request->commodity_expiry_date[$i]))
            	        //     : null,
    			    ];

    			    // // Handle image upload
    			    // if ($request->hasFile("commodity_image.$i")) { 
    			    //     $path = 'submittedSurveyImage/';
    			    //     // $data['commodity_image'] = $path;
    			    //     $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path) ?? null;
    			    //     // $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path)??null;
    			    // }
    			    // else{
    			    // 	$data['commodity_image'] = null;
    			    // }

    			    // Check if commodity exists
    			    if (in_array($commodityId, $existingSurveys)) {
    			        // Update existing record

    			        // Handle image upload
    			        if ($request->hasFile("commodity_image.$i")) { 
    			            $path = 'submittedSurveyImage/';
    			            // $data['commodity_image'] = $path;
    			            $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path) ?? null;
    			            // $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path)??null;
    			        }
    			        else{
    			        	$old = SubmittedSurvey::where('zone_id', $request->zone_id)
    			            ->where('survey_id', $request->survey_id)
    			            ->where('market_id', $request->market_id)
    			            ->where('category_id', $request->category_id)
    			            ->where('commodity_id', $commodityId)
    			            ->where('is_save', 1)->first();

    			        	$data['commodity_image'] = @$old->commodity_image??null;
    			        }

    			        SubmittedSurvey::where('zone_id', $request->zone_id)
    			            ->where('survey_id', $request->survey_id)
    			            ->where('market_id', $request->market_id)
    			            ->where('category_id', $request->category_id)
    			            ->where('commodity_id', $commodityId)
    			            ->where('is_save', 1)
    			            ->update($data);
    			    } else {
    			        // Insert new record

    			        // Handle image upload
    			        if ($request->hasFile("commodity_image.$i")) { 
    			            $path = 'submittedSurveyImage/';
    			            // $data['commodity_image'] = $path;
    			            $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path) ?? null;
    			            // $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path)??null;
    			        }
    			        else{
    			        	$data['commodity_image'] = null;
    			        }

    			        $data['commodity_id'] = $commodityId;
    			        $surveyData[] = $data;
    			    }
    			}

    			// Bulk insert if there are new records
    			// return $updateData;
    			if (!empty($surveyData)) {
    			    SubmittedSurvey::insert($surveyData);
    			}

    			// Retrieve the saved/updated data
    			$savedData = SubmittedSurvey::with('zone', 'survey', 'market', 'category', 'unit', 'brand', 'commodity')
    			    ->where([
    			        'zone_id' => $request->zone_id,
    			        'survey_id' => $request->survey_id,
    			        'market_id' => $request->market_id,
    			        'category_id' => $request->category_id,
    			        'is_submit' => 0,
    			        'is_save' => 1
    			    ])->get();

    			// Modify image URL for response
    			if ($savedData->count() > 0) {
    			    foreach ($savedData as $value) {
    			        $value->commodity_image = url('submittedSurveyImage/') . '/' . $value->commodity_image;
    			    }
    			}

    			return apiResponse(true, "Survey saved/updated successfully.", $savedData, 200);
    		}else{
    			return apiResponse(false, "No commodity selected", null, 404);
    		}
    		// return count($request->commodity_id);
    		
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    // public function saveSurvey(Request $request){
    // 	try{
    // 		// $validation = Validator::make($request->all(), [
    // 		// 	'user_id' => 'required|integer',
    // 		// 	'zone_id' => 'required|integer',
    // 		// 	'survey_id' => 'required|integer',
    // 		// 	'market_id' => 'required|integer',
    // 		// 	'category_id' => 'required|integer',
    // 		// 	'commodity_id' => 'required|array', // Ensure it's an array
    // 		// 	'commodity_id.*' => 'required|integer', // Validate each value as an integer
    // 		// 	'unit_id' => 'required|array',
    // 		// 	'unit_id.*' => 'required|integer',
    // 		// 	'brand_id' => 'required|array',
    // 		// 	'brand_id.*' => 'required|integer',
    // 		// 	'amount' => 'required|array',
    // 		// 	'amount.*' => 'required|regex:/^\d+(\.\d{1,2})?$/', // Validate each amount value
    // 		// 	'submitted_by' => 'required|integer',
    // 		// 	'availability' => 'required|array',
    // 		// 	'availability.*' => 'required|string|in:low,moderate,high',
    // 		// 	'commodity_expiry_date' => 'required|array',
    // 		// 	'commodity_expiry_date.*' => 'required|date|after_or_equal:today',
    // 		// 	'commodity_image' => 'required|array',
    // 		// 	'commodity_image.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
    // 		// ]);
    // 		// if($validation->fails()){
    // 		//     return apiResponse(false, 'Validation error', $validation->errors(), 422);
    // 		// }

    // 		$existingSurveys = SubmittedSurvey::where('zone_id', $request->zone_id)
    // 		    ->where('survey_id', $request->survey_id)
    // 		    ->where('market_id', $request->market_id)
    // 		    ->where('category_id', $request->category_id)
    // 		    ->where('is_save', 1)
    // 		    ->pluck('commodity_id')
    // 		    ->toArray();

    // 		$existingData = SubmittedSurvey::where('zone_id', $request->zone_id)
    // 		    ->where('survey_id', $request->survey_id)
    // 		    ->where('market_id', $request->market_id)
    // 		    ->where('category_id', $request->category_id)
    // 		    ->where('is_save', 1)
    // 		    ->get();

    // 		$surveyData = [];

    // 		foreach ($request->commodity_id as $i => $commodityId){
    // 		    if(in_array($commodityId, $existingSurveys)){
    // 		    	if(count($existingData)>0){
    // 		    		foreach ($existingData as $skey => $svalue) {
    // 		    			$svalue->commodity_image = url('submittedSurveyImage/').'/'.$svalue->commodity_image;
    // 		    		}
    // 		    	}
    // 		        return apiResponse(true, "A survey for this commodity ID $commodityId has already been saved.", $existingData, 200);
    // 		    }

    // 		    $data = [
    // 		        'user_id' => $request->user_id,
    // 		        'zone_id' => $request->zone_id,
    // 		        'survey_id' => $request->survey_id,
    // 		        'market_id' => $request->market_id,
    // 		        'category_id' => $request->category_id,
    // 		        'submitted_by' => $request->user_id,
    // 		        'is_submit' => false,
    // 		        'is_save' => true,
    // 		        'commodity_id' => $commodityId,
    // 		        'unit_id' => $request->unit_id[$i] ?? null,
    // 		        'brand_id' => $request->brand_id[$i] ?? null,
    // 		        'amount' => $request->amount[$i] ?? null,
    // 		        'availability' => $request->availability[$i] ?? null,
    // 		        'commodity_expiry_date' => isset($request->commodity_expiry_date[$i])
    // 		            ? date('Y-m-d', strtotime($request->commodity_expiry_date[$i]))
    // 		            : null,
    // 		    ];

    // 		    if ($request->hasFile("commodity_image.$i")) {
    // 		        $path = 'submittedSurveyImage/';
    // 		        $data['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path);
    // 		    }

    // 		    $surveyData[] = $data;
    // 		}
    // 		if (!empty($surveyData)) {
    // 		    SubmittedSurvey::insert($surveyData);
    // 		}
    // 		$savedData =SubmittedSurvey::with('zone', 'survey', 'market', 'category', 'unit', 'brand', 'commodity')->where(['zone_id' => $request->zone_id, 'survey_id' => $request->survey_id, 'market_id' => $request->market_id, 'category_id' => $request->category_id, 'is_submit'=>0, 'is_save'=>1])->get();
    // 		if(count($savedData)>0){
    // 			foreach ($savedData as $key => $value) {
    // 				$value->commodity_image = url('submittedSurveyImage/').'/'.$value->commodity_image;
    // 			}
    // 		}
    // 		return apiResponse(true, "Survey saved successfully.", $savedData, 200);
    // 	}catch(\Exception $e){
    // 		return apiResponse(false, $e->getMessage(), null, 500);
    // 	}
    // }

    public function savedSurveyList(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id' => 'required|integer',  // USER ID (SURVEYOR)
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$data = Survey::whereHas('surveyors', function($query) use ($request){
    			$query->where('surveyor_id', $request->id);
    		})
    		->whereHas('submittedSurveys', function($query1){
    			$query1->where(['is_submit'=>0, 'is_save'=>1]);
    		})->with('surveyType')->orderby('id', 'desc')->get();

    		foreach($data as $key=> $value){
    			$value->type = ($value->surveyType)?strtolower($value->surveyType->name):'';
    		}

    		if(count($data)>0){
    			return apiResponse(true, 'Record found successfully.', $data, 200);
    		}else{
    			return apiResponse(false, 'No Record found', null, 404);
    		}
    	}catch(\Exception $e){
    		return apiResponse(false, $e->getMessage(), null, 500);
    	}
    }

    public function approveSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'role' => 'required',  // ROLE
    			'user_id' => 'required|integer',  // USER ID
    			'survey_id' => 'required|integer',  // SURVEY ID
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$query = Survey::where('id', $request->survey_id);
			if(strtolower($request->role) == 'investigation officer'){
				$query->where('investigation_officer', $request->user_id);
			}
			if(strtolower($request->role) == 'chief investigation officer'){
				$query->where('chief_investigation_officer', $request->user_id);
			}
			$survey = $query->first();
			if($survey){

				$submittedSurvey = SubmittedSurvey::where('survey_id', $survey->id)->get();
				if(count($submittedSurvey)>0){
					$result = Survey::where('id', $request->survey_id)->update(['is_approve'=>'1', 'is_complete'=>1]);
					SubmittedSurvey::where('survey_id', $survey->id)->update(['status'=>'1', 'is_save'=>1, 'is_submit'=>1]);
					if($result){
						return apiResponse(true, 'Survey approved successfully.', null, 200);
					}else{
						return apiResponse(false, "Something went wrong", null, 500);
					}
				}else{
					return apiResponse(false, 'No Survey has been done on this', null, 404);
				}
			}else{
				return apiResponse(false, 'No Record found', null, 404);
			}

    	}catch(Exception $e){
    		return apiResponse(false, $validation->errors(), null, 500);
    	}
    }

    public function publishSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'role' => 'required',  // USER ROLE
    			'user_id' => 'required|integer',  // USER ID
    			'survey_id' => 'required|integer',  // SURVEY ID
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$query = Survey::where('id', $request->survey_id);
			if(strtolower($request->role) == 'investigation officer'){
				return apiResponse(false, "You don't have the right to publish the survey.", null, 403);
				// $query->where('investigation_officer', $request->user_id);
			}
			if(strtolower($request->role) == 'chief investigation officer'){
				$query->where('chief_investigation_officer', $request->user_id);
			}
			$survey = $query->first();
			if($survey){
				$submittedSurvey = SubmittedSurvey::where('survey_id', $request->survey_id)->get();
				if(count($submittedSurvey)>0){
					$result = SubmittedSurvey::where('survey_id', $request->survey_id)->update(['publish'=>'1']);
					$surveyIds = explode(" ", $request->survey_id);
					$userId = $request->user_id;
					$type = 'publish';
					// $type = 'unpublish';
					event(new PublishEvent($surveyIds, $userId, $type));

					if($result){
						return apiResponse(true, 'Survey published successfully.', null, 200);
					}else{
						return apiResponse(false, "Something went wrong", null, 500);
					}
				}else{
					return apiResponse(false, 'No Survey has has been done on this', null, 404);
				}
			}else{
				return apiResponse(false, 'No Record found', null, 404);
			}

    	}catch(Exception $e){
    		return apiResponse(false, $validation->errors(), null, 500);
    	}
    }

    public function unpublishSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'role' => 'required',  // USER ROLE
    			'user_id' => 'required|integer',  // USER ID
    			'survey_id' => 'required|integer',  // SURVEY ID
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$query = Survey::where('id', $request->survey_id);
			if(strtolower($request->role) == 'investigation officer'){
				return apiResponse(false, "You don't have the right to unpublish the survey.", null, 403);
				// $query->where('investigation_officer', $request->user_id);
			}
			if(strtolower($request->role) == 'chief investigation officer'){
				$query->where('chief_investigation_officer', $request->user_id);
			}
			$survey = $query->first();
			if($survey){
				$submittedSurvey = SubmittedSurvey::where('survey_id', $request->survey_id)->get();
				if(count($submittedSurvey)>0){
					$result = SubmittedSurvey::where('survey_id', $request->survey_id)->update(['publish'=>'0']);

					$userId = $request->user_id;
					$type = 'unpublish';
					event(new PublishEvent($request->survey_id, $userId, $type));

					if($result){
						return apiResponse(true, 'Survey unpublished successfully.', null, 200);
					}else{
						return apiResponse(false, "Something went wrong", null, 500);
					}
				}else{
					return apiResponse(false, 'No Survey has has been done on this', null, 404);
				}
			}else{
				return apiResponse(false, 'No Record found', null, 404);
			}

    	}catch(Exception $e){
    		return apiResponse(false, $validation->errors(), null, 500);
    	}
    }

    public function submitOfflineSurvey(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'survey_id' => 'required|integer',
    		]);

    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

			$survey = Survey::find($request->survey_id);
			if($survey){
				$submittedSurvey = SubmittedSurvey::where('survey_id', $request->survey_id)->get();
				if(count($submittedSurvey)>0){
					$result = SubmittedSurvey::where('survey_id', $request->survey_id)->update(['is_submit'=>1]);

					if($result){
						return apiResponse(true, 'Survey submitted successfully.', null, 200);
					}else{
						return apiResponse(false, "Something went wrong", null, 500);
					}
				}else{
					return apiResponse(false, 'No Survey has has been done on this', null, 404);
				}
			}else{
				return apiResponse(false, 'Invalid Survey Id', null, 404);
			}

    	}catch(Exception $e){
    		return apiResponse(false, $validation->errors(), null, 500);
    	}
    }


    
}
