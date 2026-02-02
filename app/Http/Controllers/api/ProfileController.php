<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\Zone;
use App\Models\FAQ;
use App\Models\UOM;
use App\Models\Brand;
use App\Models\Survey;
use App\Models\Market;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\SubmittedSurvey;

class ProfileController extends Controller
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
    public function index(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'id'=> 'required|integer',
            ]);
            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            // $endDate = date('Y-m-d', strtotime($value->end_date));
            // if($value->is_complete == false && ($endDate>$today)){
            //  $value->is_overdue = true;
            // }
            // else{
            //  $data[$key]['is_overdue'] = false;
            // }

            $user = User::find($request->id);
            
            $today = date('Y-m-d');
            
            if($user){
                // $countOfSubmittedSurvey = SubmittedSurvey::where(['submitted_by'=>$request->id, 'is_submit'=>1])
                //                             ->orWhere(['updated_by'=>$request->id])->count();

                $user->image = ($user->image) ? url('admin/images/user/'.$user->image) : url('admin/images/user/profile-img.png');

                $role = strtolower($user->roles['0']->name);
                if($role == 'investigation officer'){
                    $countOfSubmittedSurvey = Survey::where(['status'=>'1', 'investigation_officer'=>$request->id])->count();

                    $completedSurvey = Survey::where(['status'=>'1', 'investigation_officer'=>$request->id])->where('is_complete', true)->count();

                    $pendingSurvey = Survey::where(['status'=>'1', 'investigation_officer'=>$request->id])->where('is_complete', false)
                    ->whereDate('end_date', '>=', $today)->count();

                    $overdueSurvey = Survey::where(['status'=>'1', 'investigation_officer'=>$request->id])
                                        ->where('is_complete', false)
                                        ->whereDate('end_date', '<', $today)
                                        ->count();
                }
                if($role == 'chief investigation officer'){
                    $countOfSubmittedSurvey = Survey::where(['status'=>'1', 'chief_investigation_officer'=>$request->id])->count();

                    $completedSurvey = Survey::where(['status'=>'1', 'chief_investigation_officer'=>$request->id])->where('is_complete', true)->count();

                    $pendingSurvey = Survey::where(['status'=>'1', 'chief_investigation_officer'=>$request->id])->where('is_complete', false)
                    ->whereDate('end_date', '>=', $today)->count();

                    $overdueSurvey = Survey::where(['status'=>'1', 'chief_investigation_officer'=>$request->id])
                                        ->where('is_complete', false)
                                        ->whereDate('end_date', '<', $today)
                                        ->count();
                }
                if($role == 'compliance officer'){
                    $countOfSubmittedSurvey = Survey::whereHas('surveyors', function ($query) use($request) {
                                            $query->where('surveyor_id', $request->id);
                                        })
                                        ->where('status', '1')->count();

                    $completedSurvey = Survey::whereHas('surveyors', function ($query) use($request) {
                                            $query->where('surveyor_id', $request->id);
                                        })
                                        ->where('is_complete', true)
                                        // ->whereDate('end_date', '>', $today)
                                        ->where('status', '1')->count();

                    $pendingSurvey = Survey::whereHas('surveyors', function ($query) use($request) {
                                            $query->where('surveyor_id', $request->id);
                                        })
                                        ->where('is_complete', false)
                                        ->whereDate('end_date', '>=', $today)
                                        ->where('status', '1')->count();

                    $overdueSurvey = Survey::whereHas('surveyors', function ($query) use($request) {
                                            $query->where('surveyor_id', $request->id);
                                        })
                                        ->where('is_complete', false)
                                        ->whereDate('end_date', '<', $today)
                                        ->where('status', '1')->count();
                }
                
                $user->is_first_login = ($user->is_first_login)?true:false;
                $user->survey_count = ($countOfSubmittedSurvey)?$countOfSubmittedSurvey:0;
                $user->completed_survey = ($completedSurvey)?$completedSurvey:0;
                $user->pending_survey = ($pendingSurvey)?$pendingSurvey:0;
                $user->overdue_survey = ($overdueSurvey)?$overdueSurvey:0;
                $user->role = strtolower($user->roles['0']->name);
                unset($user['roles']);
                return apiResponse(true, 'Record found successfully', $user, 200);
            }else{
                return apiResponse(false, 'Invali user id', null, 204);
            }
        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the user's password.
     *
     * @author Irfan Varis
     * @date   24-02-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user whose password is to be updated.
     *     - current_password (string): The user's current password.
     *     - new_password (string): The new password, which must be at least 8 characters long, confirmed, and alphanumeric (contain both letters and numbers).
     *
     * @return JsonResponse
     */
    public function updatePassword(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'id'=> 'required|integer',
                'current_password'=> 'required',
                'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ],[
                'new_password.regex' => 'The new password must be alphanumeric (contain both letters and numbers).'
            ]);
            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $user = User::find($request->id);
            if($user){
                if(!Hash::check($request->current_password, $user->password)){
                    return apiResponse(false, 'Current password is incorrect', null, 400);
                }
                $user->password = Hash::make($request->new_password);
                $user->save();
                return apiResponse(true, 'Password changed successfully', null, 200);
            }else{
                return apiResponse(false, 'Invalid user Id.', null, 204);
            }
        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the user's profile information.
     *
     * @author Irfan Varis
     * @date   24-02-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user whose profile is to be updated.
     *     - name (string): The new name of the user, must be at least 3 characters long.
     *     - image (file|null): An optional profile image. Must be a valid image (jpeg, png, jpg) with a maximum size of 10MB.
     *
     * @return JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(), 
            [
                'id' => 'required|integer',
                'name' => 'required|string|min:3|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 10 MB Max
            ]);

            if($validation->fails())
            {
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $user = User::find($request->id);

            if($user)
            {
                $user->name = $request->name;

                if($request->hasFile('image'))
                {
                    $path = 'admin/images/user/';
                    if($request->id && $user->image)
                    {
                        $oldImagePath = public_path($path . $user->image);
                        if (file_exists($oldImagePath)) 
                        {
                            unlink($oldImagePath);
                        }
                    }

                    $image = $this->imageUpload($request->file('image'), $path);
                    $user->image = $image;
                }

                $user->save();
                return apiResponse(true, 'Profile updated successfully', null, 200);
            }
            else
            {
                return apiResponse(false, 'Invalid user Id', null, 404);
            }
        }
        catch(\Exception $e)
        {
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Log out the user.
     *
     * @author Irfan User
     * @date   24-02-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user who is logging out.
     *
     * @return JsonResponse
     */
    public function logout(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }
            $user = $request->user();
            if($user){
                $token = $user->token();
                if($token){
                    $token->revoke();
                    return apiResponse(true, 'Logged out successfully', null, 200);
                }else{
                    return apiResponse(false, 'Something went wrong', null, 404);
                }
            }else{
                return apiResponse(false, 'Invalid user Id', null, 404);
            }
        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Get Survey Details Submitted By User.
     *
     * @author Irfan User
     * @date   24-02-2025
     *
     * @param Request $request
     *     - id (integer): The ID of the user who is logging out.
     *
     * @return JsonResponse
     */
    public function submittedSurveyDetails(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            // $id = $request->id;
            $id = 3;
            $survey = Survey::find($id);
            if($survey){
                $data = SubmittedSurvey::with('user', 'zone', 'survey', 'market', 'category', 'commodity', 'unit', 'brand', 'submitter', 'updater')->where('survey_id', 3)->orderby('id', 'desc')->first();
                if($data){
                    return apiResponse(true, 'Record found successfully.', $data, 200);
                }else{
                    return apiResponse(true, 'No Record found', null, 200);
                }
            }else{
                return apiResponse(false, 'Invalid Survey Id.', null, 404);
            }
        }catch(\Exception $e){
            return apiResponse(false, 'Something went wrong', null, 500);
        }
    }

    public function help()
    {
        try
        {
            $data = FAQ::select('id', 'title', 'description')->where('status', '1')->orderby('id', 'desc')->get();
            $faq_list = array();

            if(count($data)>0)
            {
                foreach($data as $values)
                {
                    $faq['id'] = $values['id'];
                    $faq['title'] = html_entity_decode(strip_tags($values['title']));
                    $faq['description'] = html_entity_decode(strip_tags($values['description']));
                    array_push($faq_list,$faq);
                }

                return apiResponse(true, 'Record found successfully.', $faq_list, 200);
            }
            else
            {
                return apiResponse(false, 'No Record found', null, 404);
            }
        }
        catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }


    
}
