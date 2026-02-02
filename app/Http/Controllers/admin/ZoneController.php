<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;



use App\Imports\ZoneImport;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

use App\Exports\ZoneExport;



use App\Models\Zone;





class ZoneController extends Controller

{

    

    public function __construct (){

        $this->middleware('permission:zone_list', ['only' => ['index']]);

        $this->middleware('permission:zone_create', ['only' => ['create', 'store']]);

        $this->middleware('permission:zone_edit', ['only' => ['edit', 'update', 'changeStatus']]);

        $this->middleware('permission:zone_delete', ['only' => ['delete']]);

    }   



    public function index(){

        $title = 'Zone List';

        $data = Zone::orderby('id', 'desc')->paginate(10);

        return view('admin.zone.index', compact('title', 'data'));

    }



    public function save(Request $request){
        $rules = [
            'name' => 'required|max:250| unique:zones,name,' . $request->id,
            'status' => 'required|in:0,1',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }



       $zone = Zone::find($request->id) ?? new Zone();



       $zone->name = $request->name;

       $zone->status = $request->status;



       $zone->save();

       return response()->json([ 'success' => true,'message' => $request->id ? 'Zone updated successfully.' : 'Zone added successfully.',

       ]); 

    }



    public function edit($id){

        $data = Zone::find($id);

        if($data){

            return response()->json(['success' => true, 'data'=>$data]);

        }else{

            return response()->json(['success' => false, 'message'=>'No record found.']);

        }

    }



    public function view($id){

        $title = 'View Zone';

        $data = Zone::find($id);

        if($data){

            return view('admin.zone.view', compact('data', 'title'));

        }else{

            return back()->with('error', 'Something went wrong.');

        }

    }



    public function delete($id){

        $zone = Zone::find($id);

        if($zone){

            $zone->delete();

            return redirect()->route('admin.zone.list')->withSuccess('Zone deleted successfully !');

        }else{

            return redirect()->back()->withSuccess('Something went wrong. Please try later');

        }

    }



    public function updateStatus(Request $request){

        $zone = Zone::find($request->id);

        if($zone){

            $zone->status = $request->status;

            $zone->save();

            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);

        }else{

            return response()->json(['success' => false, 'message'=>'Something went wrong.']);

        }

    }



    public function filter(Request $request){
        $title = 'Zone List';
        $query = Zone::query();
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

        return view('admin.zone.index', compact('title', 'data'));

    }



    public function import(){

        $title = 'Import Zone';

        return view('admin.zone.import', compact('title'));

    }



    public function importZone(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', 
        ]);
        
        try 
        {
            Excel::import(new ZoneImport, $request->file('file'));
        } 
        catch (\Exception $e) 
        {
            return redirect()->back()->with('error', 'Import Failed: There was an issue importing your Excel file.');
        }

        return redirect()->route('admin.zone.list')->withSuccess('Zone Imported Successfully!');
    }



    public function exportZone(Request $request){

        $filters = $request->only(['name', 'status', 'start_date', 'end_date']);

        return Excel::download(new ZoneExport($filters), 'zone.xlsx');

    }





}

