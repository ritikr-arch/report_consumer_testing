<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Enquiry;
use App\Models\EnquiryCategory;

class EnquiryController extends Controller
{
    

    public function index(){
        $title = 'Enquiry List';
        $data = Enquiry::with('enquiryCategory')->orderby('id', 'desc')->paginate(10);
        return view('admin.enquiry.index', compact('title', 'data'));
    }

    public function filter(Request $request){
        $title = 'Enquiry List';
        $query = Enquiry::query();
        if($request->name){
            $query->where('name', 'like', '%'. $request->name. '%');
        }
        if($request->email){
            $query->where('email', $request->email);
        }
        if($request->phone){
            $query->where('phone', $request->phone);
        }
        if($request->type){
            $query->where('type', $request->type);
        }
        if($request->start_date){
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if($request->end_date){
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.enquiry.index', compact('title', 'data'));
    }

    public function update(Request $request){
        // $result = Enquiry::where('id', $request->id)->update(['is_read'=>'1']);
        $enquiry = Enquiry::find($request->id);
        $enquiry->is_read = '1';
        $enquiry->save();
        return response()->json(["success" => true, "data" => $enquiry]);
        // if($result){
        //     return response()->json(["success" => true, "data" => $result]);
        // }else{
        //     return response()->json(["success" => false, "message" => "No record found."]);
        // }
    }

    public function enquiryCategories(Request $request){
        $title = 'Enquiry Category List';
        // $data = EnquiryCategory::orderby('id', 'desc')->paginate(10);
        $query = EnquiryCategory::query();
        if($request->name){
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(($request->status === '0') || ($request->status === '1')){
            $query->where('status', $request->status);
        }
        if($request->start_date){
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if($request->end_date){
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        $data = $query->orderby('id', 'desc')->paginate(10);
        return view('admin.enquiry.category', compact('title', 'data'));
    }

    public function saveCategory(Request $request){
        $rules = [
            "name" => "required|max:250",
            "status" => "required|in:0,1",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
        try{
            // $result = EnquiryCategory::create(["name" => $request->name,"status" => $request->status]);
            $result = EnquiryCategory::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'status' => $request->status
                ]
            );
            return response()->json(["success" => true, "message" => $request->id ? "Category updated successfully." : "Category added successfully."]);
        }catch(\Exception $e){
            return response()->json(["success" => false,"message" => "Something went wrong.","error" => $e->getMessage(),],500);
        }

    }

    public function updateStatus(Request $request)
    {
        $survey = EnquiryCategory::find($request->id);

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

    public function edit($id){
        $data = EnquiryCategory::find($id);

        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }

    public function delete($id){
        $category = EnquiryCategory::find($id);
        if($category){
            $category->delete();
            return redirect()->route('admin.enquiry.category')->withSuccess('Category deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }


}
