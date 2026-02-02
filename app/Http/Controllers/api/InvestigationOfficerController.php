<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use Illuminate\Support\Carbon;

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

use DB;

class InvestigationOfficerController extends Controller
{

    use ImageTrait;

    /**
     * Get user profile.
     *
     * @author Irfan User
     * @date   04-04-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user who is logging in.
     *
     * @return JsonResponse
     */
    public function index(Request $request){
    	try{
    		$validation = Validator::make($request->all(), [
    			'id'=>'required|integer', //ID = Survey ID
    		]);
    		if($validation->fails()){
    			return apiResponse(false, 'Validation error', $validation->errors(), 422);
    		}

    		$data = Survey::with(['zone:id,name', 'markets', 'categories'])
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

    		if($data){
	    		$markets = Market::select('id', 'name')
	    								->whereIn('id', $data->markets->pluck('market_id'))
	    								->orderby('name', 'asc')->where('status', '1')->get();

				$categories = Category::select('id', 'name')
										->whereIn('id', $data->categories->pluck('category_id'))
										->orderby('name', 'asc')->where('status', '1')->get();

				$data->setRelation('markets', $markets);
				$data->setRelation('categories', $categories);

				return apiResponse(true, 'Record found successfully.', $data, 200);
    		}else{
    			return apiResponse(false, 'Invalid survey Id', null, 404);
    		}
    	}catch(\Exception $e){
    		return $e->getMessage();
    	}
    }

    public function assignedCommodity(Request $request){
    	try {
	        $validation = Validator::make($request->all(), [
	        	'role' => 'required',
	            'user_id' => 'required|integer',
	            'survey_id' => 'required|integer',
	            'market_id' => 'required|integer',
	            'category_id' => 'required|integer',
	        ]);

	        if ($validation->fails()) {
	            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validation->errors() ], 422);
	        }

    		$query = Survey::query();

			if(strtolower($request->role) == 'investigation officer'){
				$query->where('investigation_officer', $request->user_id);
			}
			if(strtolower($request->role) == 'chief investigation officer'){
				$query->where('chief_investigation_officer', $request->user_id);
			}

			$checkValidUser = $query->first();

	        if($checkValidUser){
    	        $existingSurvey = SubmittedSurvey::with('commodity.brand', 'commodity.uom')->where([
    	            'survey_id' => $request->survey_id,
    	            'market_id' => $request->market_id,
    	            'category_id' => $request->category_id,
    	        ])->where('is_submit', 1)->get();

    	        if (count($existingSurvey)>0) {
    	        	// $ids = $existingSurvey->pluck('commodity_id');
    	        	
    	        	// $commodity = Commodity::with('brand', 'uom')->where(['category_id'=>$request->category_id, 'status'=>'1'])->whereNotIn('id', $ids)->orderby('id', 'desc')->get();

    	        	// $existingSurvey = $existingSurvey->concat($commodity);
    	        	// foreach ($commodity as $ckey => $commodityValue) {
    	        	// 	$commodityValue->commodity = clone $commodityValue;
    	        	// 	$commodityValue->is_save = false;
    	        	// 	$commodityValue->is_submit = false;
    	        	// 	$commodityValue->user_id = null;
    	        	// 	$commodityValue->zone_id = null;
    	        	// 	$commodityValue->survey_id = null;
    	        	// 	$commodityValue->market_id = null;
    	        	// 	$commodityValue->commodity_id = $commodityValue->id;
    	        	// 	$commodityValue->unit_id = null;
    	        	// 	$commodityValue->amount = null;
    	        	// 	$commodityValue->availability = null;
    	        	// 	$commodityValue->commodity_image = null;
    	        	// 	$commodityValue->submitted_by = null;
    	        	// 	$commodityValue->updated_by = null;
    	        	// 	$commodityValue->status = null;
    	        	// 	$commodityValue->publish = null;
    	        	// 	$commodityValue->commodity_expiry_date = null;
    	        	// 	$commodityValue->zone = null;
    	        	// 	$commodityValue->survey = null;
    	        	// 	$commodityValue->market = null;
    	        	// 	$commodityValue->category = null;
    	        	// 	$commodityValue->unit = $commodityValue->uom;
    	        	// 	$commodityValue->brand = null;
    	        	// }
    	        	// return $existingSurvey;
    	        	foreach ($existingSurvey as $key => $value) {
    	        		$value->is_save = ($value->is_save == 1)?true:false;
    	        		$value->is_submit = ($value->is_submit == 1)?true:false;
    	        		if($value->commodity_image){
    	        			$value->commodity_image = url('submittedSurveyImage/').'/'.$value->commodity_image;
    	        		}
    	        		$value->amount = ($value->amount>0)?$value->amount:null;
    	        	}
    	        	return apiResponse(true, 'Saved Survey with this category.', $existingSurvey, 200);
    	        }else{
    		        return apiResponse(false, 'No Survey saved with this category.', null, 404);
    	        }
	        }else{
	        	return apiResponse(false, 'Invalid user id.', null, 404);
	        }
	    } catch (\Exception $e) {
	    	return apiResponse(false, $e->getMessage(), null, 500);
	    }
    }

    public function approveSurvey(Request $request){
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

    	// 	$query = Survey::where('id', $request->survey_id);
    	// 	if($request->role == 'investigation officer'){
    	// 		$query->where('investigation_officer');
    	// 	}
    	// 	if($request->role == 'chief investigation officer'){
 				// $query->where('chief_investigation_officer');
    	// 	}

	    //     $checkAssignedSurvey = $query->first();
	    //     if(!$checkAssignedSurvey){
	    //     	return apiResponse(false, "Invalid credentials.", null, 422);
	    //     }

    		DB::statement("SET @user_id = " . $request->user_id);

            $existingSurveys = SubmittedSurvey::where('zone_id', $request->zone_id)
                ->where('survey_id', $request->survey_id)
                ->where('market_id', $request->market_id)
                ->where('category_id', $request->category_id)
                ->where('status', '1')
                ->pluck('commodity_id')
                ->toArray();

            $surveyData = [];
            if(isset($request->submited_survey_id) && count($request->submited_survey_id)>0){
            	foreach ($request->commodity_id as $i => $commodityId) {
            	    if (in_array($commodityId, $existingSurveys)) {
            	        return apiResponse(false, "A survey for this commodity ID $commodityId has already been completed.", null, 422);
            	    }

            	    $updateData = [
            	        // 'zone_id' => $request->zone_id,
            	        // 'survey_id' => $request->survey_id,
            	        // 'market_id' => $request->market_id,
            	        // 'category_id' => $request->category_id,
            	        'status' => '1',
            	        // 'commodity_id' => $commodityId,
            	        'unit_id' => $request->unit_id[$i] ?? null,
            	        'brand_id' => $request->brand_id[$i] ?? null,
            	        // 'amount' => $request->amount[$i] ?? null,
            	        'amount' => ($request->amount[$i])?($request->amount[$i]>'0')?$request->amount[$i]:null:null  ?? null,
            	        'amount_1' => ($request->amount_1[$i])?($request->amount_1[$i]>'0')?$request->amount_1[$i]:null:null  ?? null,
            	        // 'availability' => $request->availability[$i] ?? null,
            	        'commodity_expiry_date' => isset($request->commodity_expiry_date[$i])
            	            ? $request->commodity_expiry_date[$i]
            	            : null,
            	    ];
            	    
            	    if ($request->hasFile("commodity_image.$i")) {
            	        $path = 'submittedSurveyImage/';
            	        $updateData['commodity_image'] = $this->imageUpload($request->file("commodity_image.$i"), $path)??null;
            	       
            	    }else{
        	    		$old = SubmittedSurvey::where('id', $request->submited_survey_id[$i])->first();

        	    		$updateData['commodity_image'] = @$old->commodity_image??null;
            	    }

            	    if($request->submited_survey_id[$i]){
            	    	SubmittedSurvey::where('id', $request->submited_survey_id[$i] ?? null)->update($updateData);
            	    }else{
            	    	return apiResponse(false, "No survey has been submitted yet.", null, 404);
            	    }
            	}
            	// Survey::where(['id', $request->survey_id])->update(['is_complete'=>1])

            	return apiResponse(true, "Survey approved successfully.", null, 200);
            }else{
            	return apiResponse(false, "No survey have been submitted yet.", null, 404);	
            }
            
	    } catch (\Exception $e) {
	        return apiResponse(false, $e->getMessage(), null, 500);
	    }
    }

    
}
