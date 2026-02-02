<?php

namespace App\Http\Controllers\admin;

use App\Models\ConsumerCorner;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConsumerCornerController extends Controller
{
    use ImageTrait;

    public function List(){
 
        $data = ConsumerCorner::orderby('id', 'desc')->paginate(10);
        $title = 'Consumer Corner list';
        return view('admin.consumer_corner.index', compact('data', 'title'));

    }

public function Save(Request $request)
{
    // Base validation rules (common for insert and update)
    $rules = [
        'title' => 'required|max:250',
        'status' => 'required|in:0,1',
        'type' => 'required|in:image,video_external,video_internal,link',
    ];

    // Add type-based rules dynamically
    if ($request->type === 'image') {
        $rules['document'] = $request->id
            ? 'nullable|mimes:jpeg,jpg,png,webp|max:2048'
            : 'required|mimes:jpeg,jpg,png,webp|max:2048';
    } elseif ($request->type === 'video_internal') {
        $rules['document'] = $request->id
            ? 'nullable|mimes:mp4,avi,mov|max:10240'
            : 'required|mimes:mp4,avi,mov|max:10240';
    } elseif ($request->type === 'video_external') {
        $rules['youtube_url'] = 'required|url';
    } elseif ($request->type === 'link') {
        $rules['link_url'] = 'required|url';
    }

    // Validate request
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Create or update ConsumerCorner record
    $consumer_corner = ConsumerCorner::find($request->id) ?? new ConsumerCorner();
    $path = 'admin/images/consumer_corner/';

    $consumer_corner->title = $request->title;
    $consumer_corner->status = $request->status;
    $consumer_corner->type = $request->type;

    // Handle file upload for image or internal video
    if (in_array($request->type, ['image', 'video_internal']) && $request->hasFile('document')) {
        // Delete old file if it exists
        if ($request->id && $consumer_corner->link) {
            $oldPath = public_path($path . $consumer_corner->link);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Upload and save new file
        $consumer_corner->link = $this->imageUpload($request->file('document'), $path);
    }

    // Handle external video (YouTube) link
    if ($request->type === 'video_external') {
        $consumer_corner->link = $request->youtube_url;
    }

    // Handle regular link
    if ($request->type === 'link') {
        $consumer_corner->link = $request->link_url;
    }

    // Save to database
    $consumer_corner->save();

    // Return success response
    return response()->json([
        'success' => true,
        'message' => $request->id
            ? 'Consumer Corner updated successfully.'
            : 'Consumer Corner added successfully.'
    ]);
}



    public function Edit($id){
        $data = ConsumerCorner::find($id);
        if($data){

            if($data['type'] === 'video_external' || $data['type'] === 'link'){
             $data->link = $data->link;
            }else{
             $data->link =url('admin/images/consumer_corner/'.$data->link);
            }
            
            // $data->image = 
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }


    public function updateStatus(Request $request){
        $consumer_corner = ConsumerCorner::find($request->id);
        if($consumer_corner){
            $consumer_corner->status = $request->status;
            $consumer_corner->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function Delete($id){
        $consumer_corner = ConsumerCorner::find($id);
        if($consumer_corner){
            $image = @$consumer_corner->image;
            if($image){
                $oldImagePath = public_path('admin/images/consumer_corner/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $consumer_corner->delete();
            return redirect()->route('admin.consumer_corner.list')->withSuccess('Consumer Corner deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    
    public function view($id){
        $title = 'View Consumer Corner';
        $data = ConsumerCorner::find($id);
        if($data){
            return view('admin.consumer_corner.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function filter(Request $request){
        $title = 'Consumer Corner List';
        $query = ConsumerCorner::query();
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
        return view('admin.consumer_corner.index', compact('title', 'data'));
    }


}
