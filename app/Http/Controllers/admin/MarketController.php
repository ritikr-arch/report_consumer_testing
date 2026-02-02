<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Traits\ImageTrait;



use App\Imports\MarketImport;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\MarketExport;

use Illuminate\Validation\Rule;


use App\Models\Zone;

use App\Models\Market;



class MarketController extends Controller

{
    use ImageTrait;

    public function __construct (){
        $this->middleware('permission:market_list', ['only' => ['index']]);
        $this->middleware('permission:market_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:market_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:market_delete', ['only' => ['delete']]);

    }



    public function index(){
        $title = 'Market List';
        $zone = Zone::where('status', '1')->orderby('name', 'asc')->get();
        $data = Market::orderby('id', 'desc')->paginate(10);
        return view('admin.market.index', compact('title', 'data', 'zone'));

    }



    public function save(Request $request){


        $rules = [
            'name' => [
                'required',
                'max:250',
                Rule::unique('markets')->where(function ($query) use ($request) {
                    return $query->where('zone_id', $request->zone);
                })->ignore($request->id), 
            ],
            'address' => 'nullable|string',
            'zone' => 'required|integer',
            'status' => 'required|in:0,1',
        ];

        // If no ID exists (adding new category), image is required
        if (!$request->id) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048'; // 2 MB Max
        } else {
            // If updating, image is optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $zone = Zone::find($request->zone);
        $zoneName = @$zone->name;
        $zoneDetail = json_encode(@$zone);
        $market = Market::find($request->id) ?? new Market();
        
        $market->zone_id = $request->zone;
        $market->name = $request->name;
        $market->address = $request->address;
        $market->zone_name = $zoneName;
        $market->zone_details = $zoneDetail;
        $market->status = $request->status;
        
       if ($request->hasFile('image')) {

           $path = 'admin/images/market/';
           if ($request->id && $market->image) {
               $oldImagePath = public_path($path . $market->image);
               if (file_exists($oldImagePath)) {
                   unlink($oldImagePath);
               }
           }
           $image = $this->imageUpload($request->file('image'), $path);
           $market->image = $image;
       }
       $market->save();
       return response()->json([ 'success' => true,'message' => $request->id ? 'Market updated successfully.' : 'Market added successfully.',

       ]); 

    }

    public function edit($id)
    {
        $data = Market::find($id);

        if ($data) {
            // Check if image exists, otherwise use default
            $imagePath = !empty($data->image) 
                ? 'admin/images/market/' . $data->image 
                : 'admin/images/users/avatar-3.jpg';

            $data->image = url($imagePath);

            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No record found.']);
        }
    }



    public function view($id){
        $title = 'View Market';
        $data = Market::find($id);
        if($data){
            return view('admin.market.view', compact('data', 'title'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }

    }



    public function delete($id){
        $market = Market::find($id);
        if($market){
            $image = @$market->image;
            if($image){
                $oldImagePath = public_path('admin/images/market/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $market->delete();
            return redirect()->route('admin.market.list')->withSuccess('Market deleted successfully !');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

    }

    public function updateStatus(Request $request){
        $market = Market::find($request->id);
        if($market){
            $market->status = $request->status;
            $market->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);

        }

    }



    public function filter(Request $request){

        $title = 'Market List';

        $zone = Zone::where('status', '1')->orderby('name', 'asc')->get();

        $query = Market::query();

        if($request->zone_id){

            $query->where('zone_id', $request->zone_id);

        }

        if($request->name){

            $query->where('name', 'like', '%' . $request->name . '%');

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

        return view('admin.market.index', compact('title', 'data', 'zone'));

    }



    public function import(){

        $title = 'Import Market';

        return view('admin.market.import', compact('title'));

    }



    public function importMarket(Request $request){

        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // Excel file
        ]);

        try 
        {
            Excel::import(new MarketImport, $request->file('file'));
        } 
        catch (\Exception $e) 
        {
            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.market.list')->withSuccess('Store Imported Successfully!');

    }



    public function exportMarket(Request $request){

        $filters = $request->only(['name', 'zone_id', 'status', 'start_date', 'end_date']);

        return Excel::download(new MarketExport($filters), 'Stores.xlsx');

    }





}

