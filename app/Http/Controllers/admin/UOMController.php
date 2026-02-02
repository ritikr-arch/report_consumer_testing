<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Imports\UOMImport;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\UOMExport;

use App\Models\UOM;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Category;
use Illuminate\Validation\Rule;

class UOMController extends Controller
{

    public function __construct (){

        $this->middleware('permission:uom_list', ['only' => ['index']]);

        $this->middleware('permission:uom_create', ['only' => ['create', 'store']]);

        $this->middleware('permission:uom_edit', ['only' => ['edit', 'update', 'changeStatus']]);

        $this->middleware('permission:uom_delete', ['only' => ['delete']]);

    }

    

    public function index(){

        $title = 'Unit Of Measurement';
        $type = Type::where('status', '1')->orderby('name', 'asc')->get();
        $category = Category::where('status', '1')->orderby('name', 'asc')->get();
        $brand=brand::where('status', '1')->orderby('name', 'asc')->get();
        $data = UOM::orderby('id', 'desc')->paginate(10);
        return view('admin.uom.index', compact('title', 'data','type','category','brand'));

    }

    public function getBrandsByCategory(Request $request)
    {
       
        $brands = Brand::where('status', '1')
            ->where('category_id', $request->category_id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($brands);
    }

    public function getUomsByBrand(Request $request)
    {
        $uoms = Uom::where('status', '1')
            ->where('brand_id', $request->brand_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']); // Only return necessary fields

        return response()->json($uoms);
    }



    public function save(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:250',
                Rule::unique('uom')
                    ->where(function ($query) use ($request) {
                        return $query->where('brand_id', $request->brand_id)
                                    ->where('categories_id', $request->category_id);
                    })
                    ->ignore($request->id), // For update case
            ],
            'status' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uom = UOM::find($request->id) ?? new UOM();
        $uom->name = $request->name;
        $uom->status = $request->status;
        $uom->categories_id = $request->category_id;
        $uom->brand_id = $request->brand_id;

        $uom->save();

        return response()->json([
            'success' => true,
            'message' => $request->id
                ? 'Unit Of Measurement updated successfully.'
                : 'Unit Of Measurement added successfully.',
        ]);
    }
    

    public function edit($id){

        $data = UOM::find($id);

        if($data){

            return response()->json(['success' => true, 'data'=>$data]);

        }else{

            return response()->json(['success' => false, 'message'=>'No record found.']);

        }

    }



    public function view($id){

        $title = 'View Unit Of Measurement';

        $data = UOM::find($id);

        if($data){

            return view('admin.uom.view', compact('data', 'title'));

        }else{

            return back()->with('error', 'Something went wrong.');

        }

    }



    public function delete($id){

        $uom = UOM::find($id);

        if($uom){

            $uom->delete();

            return redirect()->route('admin.uom.list')->withSuccess('UOM deleted successfully !');

        }else{

            return redirect()->back()->withSuccess('Something went wrong. Please try later');

        }

    }



    public function updateStatus(Request $request){

        $uom = UOM::find($request->id);

        if($uom){

            $uom->status = $request->status;

            $uom->save();

            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);

        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);

        }

    }



    public function filter(Request $request)
    {
        $title = 'Unit Of Measurement List';

        $query = UOM::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->status === '0' || $request->status === '1') {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }

        if ($request->category_id) {
            $query->where('categories_id', $request->category_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        $category = Category::where('status', '1')->orderBy('name', 'asc')->get();
        $brand = Brand::where('status', '1')->orderBy('name', 'asc')->get();

        $data = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.uom.index', compact('title', 'data', 'category', 'brand'));
    }



    public function import(){

        $title = 'Import Unit Of Measurement';

        return view('admin.uom.import', compact('title'));

    }



    public function importUOM(Request $request){

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', 
        ]);

        try
        {
            Excel::import(new \App\Imports\UOMImport, $request->file('file'));

        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.uom.list')->withSuccess('UOM Imported Successfully!');
    }



    public function exportUOM(Request $request){

        $filters = $request->only(['name', 'status', 'start_date', 'end_date']);

        return Excel::download(new UOMExport($filters), 'uom.xlsx');

    }



}

