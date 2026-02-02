<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\BrandImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Exports\BrandExport;
use App\Models\Brand;
use App\Models\Type;
use Illuminate\Validation\Rule;
use App\Models\Category;
class BrandController extends Controller
{
    public function __construct (){
        $this->middleware('permission:brand_list', ['only' => ['index']]);
        $this->middleware('permission:brand_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:brand_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:brand_delete', ['only' => ['delete']]);

    }

    public function index(){
        $title = 'Brand List';
        $type = Type::where('status', '1')->orderby('name', 'asc')->get();
        $category = Category::where('status', '1')->orderby('name', 'asc')->get();
        $data = Brand::orderby('id', 'desc')->paginate(10);
        return view('admin.brand.index', compact('title', 'data','type','category'));
    }

    public function getCategoriesByType(Request $request)
    {
        $categories = Category::where('status', '1')
            ->where('type_id', $request->type_id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($categories);
    }


public function save(Request $request) {
    $rules = [
    'name' => [
        'required',
        'max:250',
        Rule::unique('brands', 'name')
            ->where(function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->ignore($request->id), // ignore current ID for updates
    ],
    'status' => 'required|in:0,1',
];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $brand = Brand::find($request->id) ?? new Brand();
    $brand->name = $request->name;
    $brand->status = $request->status;
    $brand->type  = $request->type_id;          
    $brand->category_id = $request->category_id;  
    $brand->save();

    return response()->json([
        'success' => true,
        'message' => $request->id ? 'Brand updated successfully.' : 'Brand added successfully.',
    ]);
}

    public function edit($id){
        $data = Brand::find($id);
        if($data){
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function view($id){
        $title = 'View Brand';
        $data = Brand::find($id);
        if($data){
            return view('admin.brand.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function delete($id){
        $brand = Brand::find($id);
        if($brand){
            $brand->delete();
            return redirect()->route('admin.brand.list')->withSuccess('Brand deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function updateStatus(Request $request){
        $brand = Brand::find($request->id);
        if($brand){
            $brand->status = $request->status;
            $brand->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function filter(Request $request)
    {
        $title = 'Brand List';
        $query = Brand::query();

        if($request->name){
            $query->where('name', 'like', '%' . $request->name . '%');
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

        if ($request->type_id) {
            $query->where('type', $request->type_id);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();

        $type = Type::where('status', '1')->orderby('name', 'asc')->get();
        $category = Category::where('status', '1')->orderby('name', 'asc')->get();

        return view('admin.brand.index', compact('title', 'data', 'type', 'category'));
    }



    public function import(){
        $title = 'Import Brand';
        return view('admin.brand.import', compact('title'));

    }

    public function importBrand(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        try
        {
        
            Excel::import(new BrandImport, $request->file('file'));

        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.brand.list')->withSuccess('Brand Imported Successfully!');
    }

    public function exportBrand(Request $request){
        $filters = $request->only(['name', 'status', 'start_date', 'end_date']);
        return Excel::download(new BrandExport($filters), 'Brands.xlsx');

    }

}