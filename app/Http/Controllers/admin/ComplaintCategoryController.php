<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ComplaintCategory;


class ComplaintCategoryController extends Controller
{

   
    public function __construct (){
        $this->middleware('permission:complaint_category_list', ['only' => ['index']]);
        $this->middleware('permission:complaint_category_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:complaint_category_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:complaint_category_delete', ['only' => ['delete']]);

    }

    

    public function index(){
        $title = 'Category List';
        $data = ComplaintCategory::orderby('id', 'desc')->paginate(10);
        // dd( $data);
        return view('admin.complaint_category.index', compact('title', 'data'));
    }


    public function save(Request $request){

        // dd($request->all());
        $rules = [
            'name' => 'required|max:250 | unique:categories,name,' . $request->id,
            'status' => 'required|in:0,1',        
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }

       $category = ComplaintCategory::find($request->id) ?? new ComplaintCategory();

       // Set Data
       $category->name = $request->name;
       $category->status = $request->status;
       $category->save();

       return response()->json([ 'success' => true,'message' => $request->id ? 'Category updated successfully.' : 'Category added successfully.',

       ]);

    }



     public function edit($id)
    {
        $data = ComplaintCategory::find($id);

        if ($data) {
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
        $category = ComplaintCategory::find($id);
        if($category){
            $category->delete();
            return redirect()->route('admin.complaint.category.list')->withSuccess('Category deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function updateStatus(Request $request){
        $category = ComplaintCategory::find($request->id);
        if($category){
            $category->status = $request->status;
            $category->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }


}
