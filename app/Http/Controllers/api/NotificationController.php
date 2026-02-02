<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Notification;

class NotificationController extends Controller
{
    
    public function index(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'id'=> 'required|integer',
            ]);

            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }
            $data = Notification::where('user_id', $request->id)->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    $value->is_read = ($value->is_read)?true:false;
                }
                return apiResponse(true, 'Record found successfully.', $data, 200);
            }else{
                return apiResponse(false, 'No record found', null, 404);
            }
        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }


}
