<?php

namespace App\Http\Controllers\admin;

use App\Models\Broachers;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BroachersPresentationController extends Controller
{
    use ImageTrait;

    public function broacherList(){
        $data = Broachers::orderby('id', 'desc')->paginate(10);
        $title = 'Brochures list';
        return view('admin.broacher.list', compact('data', 'title'));

    }

    public function broacherSave(Request $request) {
        $rules = [
            'title' => 'required|max:250',
            'type' => 'required|max:250',
            'status' => 'required|in:0,1',
        ];
    
        if (!$request->id) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:2048'; // 10 MB Max
            $rules['document'] = 'required|mimes:pdf,doc,docx|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
            $rules['document'] = 'nullable|mimes:pdf,doc,docx|max:2048'; // Fixed rule
        }
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $broacher = Broachers::find($request->id) ?? new Broachers();
        $path = 'admin/images/broacher/';
    
        // Handle Image Upload
        if ($request->hasFile('image')) {
            if ($request->id && $broacher->image) {
                $oldImagePath = public_path($path . $broacher->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $broacher->image = $this->imageUpload($request->file('image'), $path);
        }
    
        // Handle Document Upload
        if ($request->hasFile('document')) {
            if ($request->id && $broacher->document) { // Fixed incorrect condition
                $oldDocPath = public_path($path . $broacher->document);
                if (file_exists($oldDocPath)) {
                    unlink($oldDocPath);
                }
            }
            $broacher->document = $this->imageUpload($request->file('document'), $path);
        }
    
        // Save Other Data
        $broacher->title = $request->title;
        $broacher->type = $request->type;
        $broacher->status = $request->status;
        $broacher->save();
    
        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Brochure & Presentation updated successfully.' : 'Brochure & Presentation added successfully.',
        ]);
    }
    

    public function broachersedit($id){
        $data = Broachers::find($id);
        if($data){
            $data->image = url('admin/images/broacher/'.$data->image);
            $data->document = url('admin/images/broacher/'.$data->document);
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }

    }

    public function updateStatus(Request $request){
        $category = Broachers::find($request->id);
        if($category){
            $category->status = $request->status;
            $category->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }

    }

    public function view($id){
        $title = 'View Brochures';
        $data = Broachers::find($id);
        if($data){
            return view('admin.broacher.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }
       
    public function broachersDelete($id){
        
        $broacher = Broachers::find($id);
            if ($broacher) {
                if (!empty($broacher->image)) {
                    $imagePath = public_path('admin/images/broacher/') . $broacher->image;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                // Handle document deletion
                if (!empty($broacher->document)) {
                    $documentPath = public_path('admin/images/broacher/') . $broacher->document;
                    if (file_exists($documentPath)) {
                        unlink($documentPath);
                    }
                }
                // Delete the record
                $broacher->delete();
            return redirect()->route('admin.broachers.presentation.list')->withSuccess('Brochure deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function filter(Request $request){
        $title = 'Brochures List';
        $query = Broachers::query();
        if($request->title){
            $query->where('title', 'like', '%' . $request->title . '%');
        }
       if(($request->type === 'Brochures') || ($request->type === 'Presentation') ){
            $query->where('type', $request->type);
        }
        if(($request->status === '0') || ($request->status === '1') ){
            $query->where('status', $request->status);
        }

        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.broacher.list', compact('title', 'data'));
    }

}