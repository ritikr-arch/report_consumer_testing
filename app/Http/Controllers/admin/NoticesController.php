<?php

namespace App\Http\Controllers\admin;

use App\Models\Notices;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NoticesController extends Controller
{
    use ImageTrait;

    public function List(){
 
        $data = Notices::orderby('id', 'desc')->paginate(10);
        $title = 'Notices list';
        return view('admin.notices.index', compact('data', 'title'));

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
    
        $notices = Notices::find($request->id) ?? new Notices();
        $path = 'admin/images/notices/';

        $notices->title = $request->title;
        $notices->content = $request->content;
        $notices->status = $request->status;
    
        // Handle Image Upload
        if ($request->hasFile('document')) {
            if ($request->id && $notices->link) {
                $oldImagePath = public_path($path . $notices->link);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $notices->link = $this->imageUpload($request->file('document'), $path);
        }

        $notices->save();
        return response()->json([ 'success' => true,'message' => $request->id ? 'Notices updated successfully.' : 'Notices added successfully.',
 
        ]);
    }

    public function Edit($id){
        $data = Notices::find($id);
        if($data){
            $data->link =url('admin/images/notices/'.$data->link);
            // $data->image = 
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }


    public function updateStatus(Request $request){
        $notices = Notices::find($request->id);
        if($notices){
            $notices->status = $request->status;
            $notices->save();
            return response()->json(['success' => true, 'message'=>'Notices updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function Delete($id){
        $notices = Notices::find($id);
        if($notices){
            $image = @$notices->image;
            if($image){
                $oldImagePath = public_path('admin/images/notices/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $notices->delete();
            return redirect()->route('admin.notices.list')->withSuccess('Notices deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    
    public function view($id){
        $title = 'View Notices';
        $data = Notices::find($id);
        if($data){
            return view('admin.notices.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function filter(Request $request){
        $title = 'Notices List';
        $query = Notices::query();
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
        return view('admin.notices.index', compact('title', 'data'));
    }


}
