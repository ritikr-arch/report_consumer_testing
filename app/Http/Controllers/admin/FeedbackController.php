<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $data = Feedback::orderBy('id','desc')->paginate(10);
        return view('admin/feedback/list',compact('data'));
    }

    public function filter(Request $request)
    {
        $query = Feedback::query();

         if($request->name){
            $query->where('name', 'like', '%'. $request->name. '%');
        }
        if($request->email){
            $query->where('email', $request->email);
        }
        if($request->start_date){
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }

        if($request->end_date){
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }

        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        return view('admin/feedback/list',compact('data'));
    }

    public function update(Request $request){
        // $result = Feedback::where('id', $request->id)->update(['is_read'=>'1']);
        $feedback = Feedback::find($request->id);
        $feedback->is_read = '1';
        $feedback->save();
        return response()->json(["success" => true, "data" => $feedback]);
    }
    

}
