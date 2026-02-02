<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SlugService;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use Auth;

use App\Models\NewsAndUpdate;

class NewsController extends Controller
{

    use ImageTrait;
    
    public function index(){
        $title = 'News List';
      $data = NewsAndUpdate::where('type', 'blog')
    ->orWhere('type', 'article')
    ->orderBy('id', 'desc')
    ->paginate(5);
        return view('admin.news.index', compact('title', 'data'));
    }

    public function save(Request $request){

        // dd($request->all());
         $rules = [
             'type' => 'required|in:blog,article',
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
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }

        $news = NewsAndUpdate::find($request->id) ?? new NewsAndUpdate();
        $news->title = $request->title;
        $news->slug = $request->slug;
        $news->description = $request->description;
        $news->status = $request->status;
        $news->type = $request->type;
        $news->added_by = Auth::id();
        
        if($request->hasFile('image')){

            $path = 'admin/images/news/';
            if($request->id && $news->image){
                $oldImagePath = public_path($path . $news->image);
                if(file_exists($oldImagePath)){
                    unlink($oldImagePath);
                }
            }
            $image = $this->imageUpload($request->file('image'), $path);
            $news->image = $image;
        }
        $news->save();
        return response()->json([ 'success' => true,'message' => $request->id ? 'Post updated successfully.' : 'Post added successfully.',

        ]); 
    }

    public function newsUniqueSlug(Request $request){
        $slugService = new SlugService();
        $slug = $slugService->generateUniqueSlug(NewsAndUpdate::class, $request->input('productName'), $request->input('id'));
        return ['error' => false, 'msg' => '', 'slug' => $slug];
    }

    public function updateStatus(Request $request){
        $news = NewsAndUpdate::find($request->id);
        if($news){
            $news->status = $request->status;
            $news->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);

        }
    }

    public function editNews($id){
        $data = NewsAndUpdate::find($id);
        if($data){
            $data->image = url('admin/images/news/'.$data->image);
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function view($id){
        $title = 'View News and Updates';
        $data = NewsAndUpdate::find($id);
        if($data){
            return view('admin.news.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }

    }

    public function filter(Request $request){
        $title = 'News List';
        $query = NewsAndUpdate::query();
        if($request->name){
            $query->where('title', 'like', '%' . $request->name . '%');
        }
        if(($request->status === '0') || ($request->status === '1') ){
            $query->where('status', $request->status);
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
        $data = $query->orderby('id', 'desc')->paginate(5)->withQueryString();
        return view('admin.news.index', compact('title', 'data'));
    }

    public function delete($id){
        $news = NewsAndUpdate::find($id);
        // return $news;
        if($news){
            $news->delete();
            return response()->json(['success' => true, 'message'=>'Post deleted successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong. Please try later.']);
        }
    }


}
