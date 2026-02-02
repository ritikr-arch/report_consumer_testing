<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Traits\ImageTrait;



use App\Imports\CommodityImport;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\CommodityExport;



use App\Models\UOM;

use App\Models\Brand;

use App\Models\Category;

use App\Models\Commodity;



class CommodityController extends Controller

{

    use ImageTrait;



    public function __construct (){

        $this->middleware('permission:commodity_list', ['only' => ['index']]);

        $this->middleware('permission:commodity_create', ['only' => ['create', 'store']]);

        $this->middleware('permission:commodity_edit', ['only' => ['edit', 'update', 'changeStatus']]);

        $this->middleware('permission:commodity_delete', ['only' => ['delete']]);

    }

    

    public function index(){

        $title = 'Commodity List';
        $uom = UOM::where('status', '1')->orderby('name', 'asc')->get();
        $brand = Brand::where('status', '1')->orderby('name', 'asc')->get();
        $category = Category::where('status', '1')->orderby('name', 'asc')->get();
        $data = Commodity::with('category', 'brand', 'uom')->orderby('id', 'desc')->paginate(10);
        // dd($data);

        return view('admin.commodity.index', compact('title', 'data', 'uom', 'brand', 'category'));

    }

    public function getBrandsByCategory(Request $request)
    {
        $brands = Brand::where('category_id', $request->category_id)
                        ->where('status', 1)
                        ->orderBy('name', 'asc')
                        ->get();

        return response()->json($brands);
    }

    public function getUomsByBrand(Request $request)
    {
        $uoms = UOM::where('brand_id', $request->brand_id)
                    ->where('status', 1)
                    ->orderBy('name', 'asc')
                    ->get();

        return response()->json($uoms);
    }



    public function save(Request $request){
        // dd($request->all());

        $rules = [
            'name' => 'required|max:250',
            'category' => 'required',
            'brand' => 'required',
            'uom' => 'required',
            // '' => 'required|array|alpha_numeric|max:5',
            'status' => 'required|in:0,1',

        ];


        if (!$request->id) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048'; // 10 MB Max
            // $rules['unit_values.*'] = 'required|string|max:255';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
            // $rules['unit_value'] = 'required|max:250'; // 10 MB Max
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('unit_values')) {
            $units = 
            $unitCount = count($request->unit_values);
            $img = '';
            for ($i=0; $i < $unitCount; $i++) { 
                $commodity = new Commodity();
                $commodity->name = $request->name;
                $commodity->status = $request->status;
                $commodity->category_id = $request->category;
                $commodity->brand_id = $request->brand;
                $commodity->uom_id = $request->uom;
                // $commodity->unit_value = $request->unit_values[$i];
                
                if ($i === 0 && $request->hasFile('image')) {
                   try {
                       $path = 'admin/images/commodity/';
                       $image = $this->imageUpload($request->file('image'), $path);
                       $commodity->image = $image;
                       $img = $image;
                   } catch (\Exception $e) {
                       return response()->json(['error' => 'Image upload failed: ' . $e->getMessage()], 500);
                   }
               }else{
                $commodity->image = $img;
               }
                $commodity->save();
            }
        } else {

            $commodity = Commodity::find($request->id) ?? new Commodity();
            $commodity->name = $request->name;
            $commodity->status = $request->status;
            $commodity->category_id = $request->category;
            $commodity->brand_id = $request->brand;
            $commodity->uom_id = $request->uom;
            $commodity->unit_value = $request->unit_value;

            if ($request->hasFile('image')) {
                $path = 'admin/images/commodity/';
                if ($request->id && $commodity->image) {
                    $oldImagePath = public_path($path . $commodity->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $this->imageUpload($request->file('image'), $path);

                $commodity->image = $image;

            }



            $commodity->save();

        }





       // $commodity = Commodity::find($request->id) ?? new Commodity();



       // $commodity->name = $request->name;

       // $commodity->status = $request->status;

       // $commodity->category_id = $request->category;

       // $commodity->brand_id = $request->brand;

       // $commodity->uom_id = $request->uom;



       // if ($request->hasFile('image')) {

       //     $path = 'admin/images/commodity/';

           

       //     if ($request->id && $commodity->image) {

       //         $oldImagePath = public_path($path . $commodity->image);

       //         if (file_exists($oldImagePath)) {

       //             unlink($oldImagePath);

       //         }

       //     }

       //     $image = $this->imageUpload($request->file('image'), $path);

       //     $commodity->image = $image;

       // }



       // $commodity->save();

       return response()->json([ 'success' => true,'message' => $request->id ? 'Commodity updated successfully.' : 'Commodity added successfully.',

       ]); 

    }



    public function edit($id)
    {
        $data = Commodity::with('category', 'brand', 'uom')->find($id);
       
        if ($data) {
            // Set image path or fallback to default image
            $imagePath = !empty($data->image) 
                ? 'admin/images/commodity/' . $data->image 
                : 'admin/images/users/avatar-3.jpg';

            $data->image = url($imagePath);

            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }



    public function view($id){

        $title = 'View Commodity';

        $data = Commodity::with('category', 'brand', 'uom')->find($id);

        if($data){

            return view('admin.commodity.view', compact('data', 'title'));

        }else{

            return back()->with('error', 'Something went wrong.');

        }

    }



    public function delete($id){

        $commodity = Commodity::find($id);

        if($commodity){

            $image = @$commodity->image;

            if($image){

                $oldImagePath = public_path('admin/images/commodity/').$image;

                if (file_exists($oldImagePath)) {

                    unlink($oldImagePath);

                }

            }

            $commodity->delete();

            return redirect()->route('admin.commodity.list')->withSuccess('Commodity deleted successfully !');

        }else{

            return redirect()->back()->withSuccess('Something went wrong. Please try later');

        }

    }



    public function updateStatus(Request $request){

        $commodity = Commodity::find($request->id);

        if($commodity){

            $commodity->status = $request->status;

            $commodity->save();

            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);

        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);

        }

    }



    public function filter(Request $request){

        $title = 'Commodity List';



        $uom = UOM::where('status', '1')->orderby('name', 'asc')->get();

        $brand = Brand::where('status', '1')->orderby('name', 'asc')->get();

        $category = Category::where('status', '1')->orderby('name', 'asc')->get();



        $query = Commodity::query();

        if($request->name){

            $query->where('name', 'like', '%' . $request->name . '%');

        }

        if($request->unit){

            $query->where('unit_value', 'like', '%' . $request->unit . '%');

        }

        if($request->category){

            $query->where('category_id',  $request->category);

        }

        if($request->brand){

            $query->where('brand_id',  $request->brand);

        }

        if($request->uom){

            $query->where('uom_id',  $request->uom);

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

        return view('admin.commodity.index', compact('title', 'data', 'category', 'uom', 'brand'));

    }



    public function import(){

        $title = 'Import Commodity';

        return view('admin.commodity.import', compact('title'));

    }



    public function importCommodity(Request $request){

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // Excel file
        ]);

        try 
        {
            Excel::import(new CommodityImport, $request->file('file'));
        } 
        catch (\Exception $e) 
        {

            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.commodity.list')->withSuccess('Commodity Imported Successfully!');

    }



    public function exportcommodity(Request $request){

        $filters = $request->only(['name', 'unit', 'category', 'brand', 'uom', 'status', 'start_date', 'end_date']);

        return Excel::download(new CommodityExport($filters), 'Commodities.xlsx');

    }



}