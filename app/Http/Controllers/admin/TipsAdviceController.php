<?php

namespace App\Http\Controllers\admin;

use App\Models\TipsAdvice;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TipsAdviceController extends Controller
{
    use ImageTrait;

    public function List(){
 
        $data = TipsAdvice::orderby('id', 'desc')->paginate(10);
        $title = 'Tips And Advice list';
        return view('admin.tips_advice.index', compact('data', 'title'));

    }

    public function Save(Request $request){
        $rules = [
            'title' => 'required|max:250',
            'content' => 'required|max:500',
            'status' => 'required|in:0,1',
        ];
    
        if (!$request->id) {
           $rules['document'] = 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:5120';

        
        } else {
            $rules['document'] = 'mimes:jpeg,png,jpg,gif,svg,webp|max:5120';

        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $tipsadvice = TipsAdvice::find($request->id) ?? new TipsAdvice();
        $path = 'admin/images/tips_advice/';

        $tipsadvice->title = $request->title;
        $tipsadvice->content = $request->content;
        $tipsadvice->status = $request->status;
    
        // Handle Image Upload
        if ($request->hasFile('document')) {
            if ($request->id && $tipsadvice->link) {
                $oldImagePath = public_path($path . $tipsadvice->link);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $tipsadvice->link = $this->imageUpload($request->file('document'), $path);
        }

        $tipsadvice->save();
        return response()->json([ 'success' => true,'message' => $request->id ? 'Tips And Advice updated successfully.' : 'Tips And Advice added successfully.',
 
        ]);
    }

    public function Edit($id){
        $data = TipsAdvice::find($id);
        if($data){
            $data->link =url('admin/images/tips_advice/'.$data->link);
            // $data->image = 
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }


    public function updateStatus(Request $request){
        $tipsadvice = TipsAdvice::find($request->id);
        if($tipsadvice){
            $tipsadvice->status = $request->status;
            $tipsadvice->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function Delete($id){
        $tipsadvice = TipsAdvice::find($id);
        if($tipsadvice){
            $image = @$tipsadvice->image;
            if($image){
                $oldImagePath = public_path('admin/images/tips_advice/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $tipsadvice->delete();
            return redirect()->route('admin.tips_advice.list')->withSuccess('Tips And Advice deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    
    public function view($id){
        $title = 'View Tips And Advice';
        $data = TipsAdvice::find($id);
        if($data){
            return view('admin.tips_advice.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function filter(Request $request){
        $title = 'Tips And Advice List';
        $query = TipsAdvice::query();
        if($request->title){
            $query->where('title', 'like', '%' . $request->title . '%');
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
        return view('admin.tips_advice.index', compact('title', 'data'));
    }


}
