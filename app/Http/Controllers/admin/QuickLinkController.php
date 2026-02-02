<?php

namespace App\Http\Controllers\admin;

use App\Models\QuickLinks;
use Illuminate\Http\Request;
use App\Services\SlugService;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QuickLinkController extends Controller
{
    
    use ImageTrait;
    
    public function index(){
        $title = 'Quick Links List';
        $data = QuickLinks::orderby('id', 'desc')->paginate(5);
        
        return view('admin.quick_links.index', compact('title', 'data'));
    }

    public function save(Request $request){

        // dd($request->all());
        $rules = [
            'title' => 'required|max:250',
            'description' => 'required',
            'status' => 'required|in:0,1',
        ];
        // If no ID exists (adding new category), image is required
        if (!$request->id) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:10240'; // 10 MB Max
        } else {
            // If updating, image is optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:10240';
        }

          if (!$request->id) {
            $rules['document'] = 'required|mimes:pdf|max:2048'; // 10 MB Max
        } else {
            // If updating, image is optional
            $rules['document'] = 'nullable|mimes:pdf|max:2048';
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       $news = QuickLinks::find($request->id) ?? new QuickLinks();

       $news->title = $request->title;
       $news->slug = $request->slug;
      
       $news->status = $request->status;

       if($request->hasFile('image')){
           $path = 'admin/images/quck_link/';
           if($request->id && $news->image){
               $oldImagePath = public_path($path . $news->image);
               if(file_exists($oldImagePath)){
                   unlink($oldImagePath);
               }
           }
           $image = $this->imageUpload($request->file('image'), $path);
           $news->image = $image;
       }

       if ($request->hasFile('document')) {
        $path = 'admin/docs/quick_link/';

            // Delete old PDF if updating
            if ($request->id && $news->document) {
                $oldPdfPath = public_path($path . $news->document);
                if (file_exists($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }

            // Upload new PDF
            $pdf = $this->fileUpload($request->file('document'), $path); // Rename imageUpload to fileUpload if needed
            $news->document = $pdf;
        }
        $news->content = $request->description;
       $news->save();
       return response()->json([ 'success' => true,'message' => $request->id ? 'Quick Links updated successfully.' : 'Quick Links added successfully.',

       ]); 
   }

   public function quickUniqueSlug (Request $request){
    $slugService = new SlugService();
    $slug = $slugService->generateUniqueSlug(QuickLinks::class, $request->input('productName'), $request->input('id'));
    return ['error' => false, 'msg' => '', 'slug' => $slug];
}

public function editQuick($id){
    $data = QuickLinks::find($id);
    if($data){
        $data->image = url('admin/images/quck_link/'.$data->image);
        return response()->json(['success' => true, 'data'=>$data]);
    }else{
        return response()->json(['success' => false, 'message'=>'No record found.']);
    }
}

public function updateStatus(Request $request){
    $news = QuickLinks::find($request->id);

    if($news){
        $news->status = $request->status;
        $news->save();
        return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
    }else{

        return response()->json(['success' => false, 'message'=>'Something went wrong.']);

    }
}

public function delete($id){
    $news = QuickLinks::find($id);
    // return $news;
    if($news){
        $news->delete();
        return response()->json(['success' => true, 'message'=>'Quick Links deleted successfully.']);
    }else{
        return response()->json(['success' => false, 'message'=>'Something went wrong. Please try later.']);
    }
}


public function filter(Request $request){
    $title = 'Quick Links List';
    $query = QuickLinks::query();
    if($request->name){
        $query->where('title', 'like', '%' . $request->name . '%');
    }
    if(($request->status === '0') || ($request->status === '1') ){
        $query->where('status', $request->status);
    }
   
    $data = $query->orderby('id', 'desc')->paginate(5)->withQueryString();

    return view('admin.quick_links.index', compact('title', 'data'));
   
}

public function view($id){
    $title = 'View quick links';
    $data = QuickLinks::find($id);
    if($data){
        return view('admin.quick_links.view', compact('data', 'title'));
    }else{
        return back()->with('error', 'Something went wrong.');
    }

}

public function fileUpload($file, $path)
{
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path($path), $filename);
    return $filename;
}





}