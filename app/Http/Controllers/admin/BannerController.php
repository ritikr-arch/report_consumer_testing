<?php

namespace App\Http\Controllers\admin;

use App\Models\CMS;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    use ImageTrait;

    public function sliderList()
    {
        $data = Banner::orderby('id', 'desc')->paginate(10);
        $title = 'Slider list';
        return view('admin.banner.list', compact('data', 'title'));
    }

    public function saveSlider(Request $request){
        $banner = Banner::find($request->id) ?? new Banner();
        $rules = [
            'status' => 'required|in:0,1',
            'upload_type' => 'required|in:image,video',
        ];

        if ($request->upload_type === 'image') 
        {
            if (!$request->id) 
            {
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
            } 
            else 
            {
                if($request->upload_type != $banner->type){
                $rules['image'] = 'required|nullable|image|mimes:jpeg,png,jpg|max:2048';
                }else{
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
                }
                
            }
        } 
        elseif ($request->upload_type === 'video') 
        {
            if (!$request->id) 
            {
                $rules['video'] = 'required|mimes:mp4,mov,avi|max:10240'; 
            } 
            else 
            {
                if($request->upload_type != $banner->type){
                $rules['video'] = 'required|mimes:mp4,mov,avi|max:10240'; 
                }else{
                $rules['video'] = 'mimes:mp4,mov,avi|max:10240'; 
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       

       // Set Data
       $banner->title = $request->title;
       $banner->status = $request->status;
       $banner->type = $request->upload_type;

       // Image Upload
       if ($request->upload_type === 'image' && $request->hasFile('image')) 
       {
           $path = 'admin/images/banner/';
           // Delete old image if updating
           if ($request->id && $banner->image) 
           {
               $oldImagePath = public_path($path . $banner->image);
               if (file_exists($oldImagePath)) 
               {
                   unlink($oldImagePath);
               }
           }

           // Upload New Image
           $image = $this->imageUpload($request->file('image'), $path);
           $banner->image = $image;
       }

        // Video Upload
        if ($request->upload_type === 'video' && $request->hasFile('video')) 
        {
            $path = 'admin/videos/banner/';
            if ($request->id && $banner->image) {
                $oldVideoPath = public_path($path . $banner->image);
                if (file_exists($oldVideoPath)) {
                    unlink($oldVideoPath);
                }
            }
            $video = $this->imageUpload($request->file('video'), $path);
            $banner->video = $video;
        }

       $banner->save();
       return response()->json([ 'success' => true,'message' => $request->id ? 'Banner updated successfully.' : 'banner added successfully.',

       ]);
       
    }

    public function editSlider($id){

        $data = Banner::find($id);
        if($data){
            $data->image = url('admin/images/banner/'.$data->image);
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }

    }


    public function deleteSlider($id)
    {
        $banner = Banner::find($id);
        
        if($banner)
        {
            if($banner->type=='image')
            {
                $image = @$banner->image;

                if($image)
                {
                    $oldImagePath = public_path('admin/images/banner/').$image;
                    if (file_exists($oldImagePath)) 
                    {
                        unlink($oldImagePath);
                    }
                }
            }
            elseif($banner->type=='video')
            {
                $video = @$banner->video;

                if($video)
                {
                    $oldvideoPath = public_path('admin/videos/banner/').$video;
                    if (file_exists($oldvideoPath)) 
                    {
                        unlink($oldvideoPath);
                    }
                }
            }
    
            $banner->delete();
            return redirect()->route('admin.banner.list')->withSuccess('Banner deleted successfully !');
        }
        else
        {
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function view($id){
        $title = 'View Banner';
        $data = Banner::find($id);
        if($data){
            return view('admin.banner.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');

        }

    }

    public function filter(Request $request){
        $title = 'Banner List';
        $query = Banner::query();
        if($request->title){
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if(($request->status === '0') || ($request->status === '1') ){
        // if($request->status){
            $query->where('status', $request->status);
        }
      
        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.banner.list', compact('title', 'data'));

    }
                                                     
    public function updateStatus(Request $request){

        
        $banner = Banner::find($request->id);
        if($banner){
            $banner->status = $request->status;
            $banner->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function banner_heading(){

        $data = CMS::where('type', 'Banner_Heading')->first();
        return view('admin.banner.add_heding', compact('data'));

    }

    public function BannerHeadingSeve(Request $request){
        $this->validate($request, [
            'heading' => 'required|max:250',
            'sub_heading' => 'required |max:250 ',
        ]);
        
        $id = ['id' => $request->id];

        $data = array(
            'title' => $request->heading,
            'content' => $request->sub_heading,
            'type' => 'Banner_Heading',
        );

        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Banner Heading updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

    }


}