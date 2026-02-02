<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Exports\CategoryExport;
use App\Models\User;
use App\Models\Category;
use App\Models\Type;

class CategoryController extends Controller
{

    use ImageTrait;
    public function __construct (){
        $this->middleware('permission:category_list', ['only' => ['index']]);
        $this->middleware('permission:category_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:category_delete', ['only' => ['delete']]);

    }

    

    public function index(){
        $title = 'Category List';
        $type = Type::where('status', '1')->orderby('name', 'asc')->get();
        $data = Category::orderby('id', 'desc')->paginate(10);

        // dd( $data);
        return view('admin.category.index', compact('title', 'data', 'type'));
    }


    public function save(Request $request){

        // dd($request->all());
        $rules = [
            'name' => 'required|max:250 | unique:categories,name,' . $request->id,
            'type_id' => 'required|max:25',
            'status' => 'required|in:0,1',        
        ];

        if (!$request->id) {

            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048'; // 10 MB Max
        } else {
            // If updating, image is optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }

       $category = Category::find($request->id) ?? new Category();

       // Set Data
       $category->name = $request->name;
       $category->type_id = $request->type_id;
       $category->status = $request->status;

       if ($request->hasFile('image')) {
           $path = 'admin/images/category/';
           // Delete old image if updating
           if ($request->id && $category->image) {
               $oldImagePath = public_path($path . $category->image);
               if (file_exists($oldImagePath)) {
                   unlink($oldImagePath);
               }
           }
           // Upload New Image
           $image = $this->imageUpload($request->file('image'), $path);
           $category->image = $image;
       }

       $category->save();

       return response()->json([ 'success' => true,'message' => $request->id ? 'Category updated successfully.' : 'Category added successfully.',

       ]);

    }



     public function edit($id)
    {
        $data = Category::find($id);

        if ($data) {
            // Use default image if $data->image is null or empty
            $imagePath = !empty($data->image) 
                ? 'admin/images/category/' . $data->image 
                : 'admin/images/users/avatar-3.jpg';

            $data->image = url($imagePath);

            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }


    public function view($id){
        $title = 'View Category';
        $data = Category::find($id);
        if($data){
            return view('admin.category.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');

        }
    }

    public function delete($id){
        $category = Category::find($id);
        if($category){
            $image = @$category->image;
            if($image){
                $oldImagePath = public_path('admin/images/category/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $category->delete();
            return redirect()->route('admin.category.list')->withSuccess('Category deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function updateStatus(Request $request){
        $category = Category::find($request->id);
        if($category){
            $category->status = $request->status;
            $category->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }



    public function filter(Request $request){
        $title = 'Category List';
        $query = Category::query();
        if($request->name){
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if(($request->status === '0') || ($request->status === '1') ){
        // if($request->status){
            $query->where('status', $request->status);
        }
        if($request->start_date){
           // $query->whereDate('created_at', $request->start_date); 
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }

        if($request->end_date){
            // $query->where('created_at', $request->end_date);
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.category.index', compact('title', 'data'));

    }



    public function import(){
        $title = 'Import Category';
        return view('admin.category.import', compact('title'));
    }

    public function importCategort(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // Excel file
        ]);

        try 
        {
            
            Excel::import(new CategoryImport, $request->file('file'));
        } 
        catch (\Exception $e) 
        {

            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.category.list')->withSuccess('Category Imported Successfully!');
    }


    public function exportCategory(Request $request){
        $filters = $request->only(['name', 'status', 'start_date', 'end_date']);
        return Excel::download(new CategoryExport($filters), 'Categories.xlsx');

    }


}
