<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Mail\ComplaintMail;
use App\Mail\ThankYouMail;
use App\Mail\OfficerAssignedNotificationMail;
use App\Mail\ComplaintStatusUpdatedMail;
use App\Models\CustomerComplaint;
use App\Models\ComplaintDocument;
use App\Models\ComplaintForm;
use App\Models\ComplaintFormStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ComplaintCategory;

class ComplaintFormController extends Controller
{   
    public function index()
    {
       $complaintCategory = ComplaintCategory::where('status', 1)->orderby('id', 'desc')->get(); 
        return view('admin.complaint_form.index',compact('complaintCategory'));
    }

    public function generate_customer_id()
    {
        $complaint = CustomerComplaint::orderBy('complaint_id','desc')->first();
       
        if(!empty($complaint))
        {
            $complaint_id = $complaint['complaint_id'];
            $complaint_id++;
            return $complaint_id;
        }
        else
        {
            $complaint_id = '100';
            return $complaint_id;
        }
    }

    public function process(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'first_name' => 'required|max:50|string',
                'email' => 'required|nullable|email|max:255',
                'country_code' => 'required',
                'phone_no' => 'required|numeric|digits_between:7,10',
                'address' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female', 
                'age_group' => 'required',
                'business_name' => 'required|string|max:50', 
                'business_address' => 'required|string|max:250', 
                'business_country_code' => 'required', 
                'business_phone' => 'required|numeric|digits_between:7,10', 
                'business_email' => 'required|email:rfc,filter|max:150', 
                'goods' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'serial' => 'required|string|max:50',
                'category' => 'required', 
                'date_purchase' => 'required|date', 
                'warranty' => 'required|in:None,14 days or less,1 month,2 month,3 month',
                'hire_purchase' => 'required|in:0,1',
                'contract' => 'required|in:0,1',
                'additional_statement' => 'nullable|string|max:500',
                'certify' => 'required', 
                'documents' => 'required|array',
                'documents.*' => 'file|max:2048|mimes:jpg,png,docx,pdf,doc',
                'date' => 'required|date', 
            ],[
                
                'goods.required' => 'The product or service purchased field is required.',
                'serial.required' => 'The model/serial number field is required.',
                'country_code.required' => 'The Country Code field is required.',
                'business_country_code.required' => 'The Bussiness Country Code field is required.',

       
            ]);

            // Signature
            $image = $request->input('signature_image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            $fileName = 'signatures/' . uniqid() . '.png';
            Storage::disk('public')->put('signatures/' .$fileName, $imageData);
           try {
                // Convert and merge 'date_purchase'
                $datePurchase = Carbon::createFromFormat('d-m-Y', $request->input('date_purchase'))->format('Y-m-d');
                $request->merge(['date_purchase' => $datePurchase]);
                $date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d');
                $request->merge(['date' => $date]);
            } catch (\Exception $e) {
                $request->merge(['date_purchase' => null]);
                $request->merge(['date' => null]);
            }

            $data=array();
            $data['complaint_id'] = $this->generate_customer_id();
            $data['first_name'] = $request->input('first_name');
            $data['last_name'] = $request->input('last_name');
            $data['email'] = $request->input('email');
            $data['country_code'] = $request->input('country_code');
            $data['phone'] = $request->input('phone_no');
            $data['address'] = $request->input('address');
            $data['gender'] = $request->input('gender');
            $data['age_group'] = $request->input('age_group');
            $data['business_name'] = $request->input('business_name');
            $data['business_email'] = $request->input('business_email');
            $data['business_country_code'] = $request->input('business_country_code');
            $data['business_phone'] = $request->input('business_phone');
            $data['business_address'] = $request->input('business_address');
            $data['service'] = $request->input('goods');
            $data['serial'] = $request->input('serial');
            $data['category'] = $request->input('category');
            $data['date_of_purchase'] = $request->input('date_purchase');
            $data['warranty'] = $request->input('warranty');
            $data['brand'] = $request->input('brand');
            $data['hire_purchase_item'] = $request->input('hire_purchase');
            $data['sign_contract'] = $request->input('contract');
            $data['additional_statement'] = $request->input('additional_statement');
            $data['signed'] = $request->input('signature_image');
            $data['date'] = $request->input('date');
            $data['email_verified'] = '1';
            $data['is_completed'] = '1';
            $data['created_at'] = date('Y-m-d h:i:s');
            $data['updated_at'] = date('Y-m-d h:i:s');

            if(!empty($request->input('email')))
            {
                // Mail
                $mail=array();
                $mail['name'] = $request->input('first_name').' '.$request->input('last_name');
                $mail['complaint_id'] = $this->generate_customer_id();

                Mail::to($request->input('email'))->send(new ThankYouMail($mail));
            }

            $com = CustomerComplaint::create($data);

            // File Upload
            if(!empty($request->file('documents')))
            {
                foreach ($request->file('documents') as $document) 
                {
                    $originalName = $document->getClientOriginalName();
                    $fileType = $document->getClientOriginalExtension();
                    $fileSize = $document->getSize();

                    $filename = Str::random(10) . '_' . $originalName;
                    $document->storeAs('documents', $filename, 'public');

                    $file=array();
                    $file['complaint_id'] = $com->id;
                    $file['document'] = $filename;
                    $file['created_at'] = date('Y-m-d h:i:s'); 

                    ComplaintDocument::create($file);
                }
            }

            return redirect()->route('admin.complaint.form.list')->with('success','Your complaint has been submitted successfully.');
        }
    }

    public function List()
    {
        
        $complaintformstatuses = ComplaintFormStatus::get();
        $user = Auth::user();
        $roles = $user->roles->pluck('name');
       
         if ($roles[0] === 'Admin') {
            $data = ComplaintForm::orderBy('id', 'desc')->paginate(10);
        } else {
            // $data = ComplaintForm::where('investing_officer', $user->name)
            //                     ->orderBy('investing_officer', 'asc')
            //                     ->paginate(10);
            $data = ComplaintForm::orderBy('id', 'desc')->paginate(10);
        }

        // $data = ComplaintForm::orderby('id', 'desc')->paginate(10);


       
        $title = 'Complaint Form list';
        $officer = User::role(['Complaint Manager', 'Chief Investigation Officer'])
            ->where('status', '1')
            ->orderBy('name', 'asc')
            ->get();

         
        return view('admin.complaint_form.list', compact('data', 'title','officer','complaintformstatuses','roles'));
    }

    public function Save(Request $request)
    {
        $rules = [
            
            'result' => 'required|max:5000',
            'end_date' => 'required|max:50',
            'status' => 'required|in:0,1,2,3,4',
        ];

        
        $messages = [
            'result.required' => 'The feedback field is required.',
            'result.max' => 'The feedback field must not be greater than 5000 characters.',
        ];
    
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) 
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Get the base64 image from input
           $image = $request->input('signature_image');

            // Remove the base64 prefix and prepare for decoding
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            // Define the destination path in the public directory
            $destination = public_path('exhibits/signatures');

            // Create the folder if it doesn't exist
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // Generate a unique file name
            $fileName = 'signature_' . uniqid() . '.png';
            $fullPath = $destination . '/' . $fileName;

            // Save the decoded image to the file
            file_put_contents($fullPath, $imageData);
                $exhibitsfilename="";
                if ($request->hasFile('exhibits')) {
                    $file = $request->file('exhibits');
                    $exhibitsfilename = time() . '_' . $file->getClientOriginalName();

                    $destination = public_path('exhibits');

                    // Ensure directory exists
                    if (!file_exists($destination)) {
                        mkdir($destination, 0775, true);
                    }

                    $file->move($destination, $exhibitsfilename);
                }
            try {
            // Convert and overwrite in the same variable
            $request->end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');
            } catch (\Exception $e) {
            // Handle invalid format if needed
            // For example, set null or throw error
            $request->end_date = null;
                }
        $complain = ComplaintForm::find($request->id);
        $complain->status = $request->status;
        $complain->investing_officer = $request->supervisior;
        $complain->save();

       $complaintFormStatus  = ComplaintFormStatus::create([
            'complaints_id' => $complain->id, // assuming $complain is the current complaint instance
            'official_use_supervisior' => $request->supervisior,
            'official_use_date' => now(), // or set your own timestamp if required
            'official_use_exhibits' => 'exhibits/'.$exhibitsfilename,
            'official_use_feedback' => $request->result,
            'official_use_signature' => 'exhibits/signatures/'.$fileName,
            'official_use_end_date' => $request->end_date,
            'official_use_remark' => $request->remark,
            'status' => $request->status,
        ]);
                if ($complaintFormStatus) {
                Mail::to($complain->email)->send(new ComplaintStatusUpdatedMail(
                    $complain->first_name . ' ' . $complain->last_name,
                    $complain->complaint_id,
                    ucfirst($complaintFormStatus->status),
                    $request->result,
                    $request->remark,
                    $request->supervisior,
                    now()->format('d M Y, h:i A')
                ));
            }
        $complains = ComplaintForm::orderby('id', 'desc')->first();

        if($request->status=='1')
        {
            Mail::to($complains->email)->send(new ComplaintMail($complains));
            
        }
        

        return response()->json([ 'success' => true,'message' => $request->id ? 'Complaint Form updated successfully.' : 'Complaint Form added successfully.']);
    }

    public function assigneedTo(Request $request){
        $complain = ComplaintForm::find($request->id);
        $complain->investing_officer = $request->investing_officer;
        $complain->official_use_date = now();
        $complain->save();
       $nameParts = explode('.', $complain->investing_officer);
       $officerName = isset($nameParts[1]) ? trim($nameParts[1]) : $complain->investing_officer;
       // Assuming officer email is stored in User model
       $officer = User::where('name', $officerName)->first();
       if ($officer && $officer->email) {
        // dd($officer->email);
        Mail::to($officer->email)->send(new OfficerAssignedNotificationMail($complain->id, $officer->name));
        }
        return response()->json([ 'success' => true,'message' => $request->id ? 'Succesfully assigned Complaint' : 'Succesfully assigned Complaint']);
    }
    public function Edit($id){
        $data = ComplaintForm::find($id);
        if($data){
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }
    }

    public function view($id){
        $title = 'View Complaint Form';
        $data = ComplaintForm::find($id);
        $complaintformstatuses = ComplaintFormStatus::where('complaints_id', $id)->where('status', '!=', '')->orderBy('id', 'desc')->get();
        $documents = ComplaintDocument::where('complaint_id',$id)->get();
        $isclosed = ComplaintFormStatus::where('complaints_id', $id)->where('status', '4')->first() ?? ['status'=>0];
        $officer = User::role(['Complaint Manager', 'Chief Investigation Officer'])
            ->where('status', '1')
            ->orderBy('name', 'asc')
            ->get();
       $user = Auth::user();
        $roles = $user->roles->pluck('name');
        if($data){
            return view('admin.complaint_form.view', compact('data', 'title', 'documents','officer','complaintformstatuses','isclosed','roles','user'));
        }else{
            return back()->with('error', 'Something went wrong.');
        }
    }


    public function filter(Request $request)
    {
        $title = 'Complaint List';

        $request->validate([
            'complaint_id' => 'nullable|string',
            'status' => 'nullable|in:0,1,2,3,4',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $query = ComplaintForm::query();
        $complaint_id = preg_replace('/^CID/', '', $request->complaint_id);

        if($request->complaint_id){
           $query->where('complaint_id', 'like', '%' . $complaint_id . '%');
        }

        if(($request->status === '0') || ($request->status === '1') || ($request->status === '2') || ($request->status === '3') || ($request->status === '4'))
        {
            $query->where('status', $request->status);
        }

        if($request->start_date){
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
        }

        if($request->end_date){
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
        }

        $data = $query->orderby('id', 'desc')->paginate(10)->withQueryString();
        $officer = User::role('Chief Investigation Officer')->where('status', '1')->orderby('name', 'desc')->get();
       $user = Auth::user();
        $roles = $user->roles->pluck('name');
        $complaintformstatuses = ComplaintFormStatus::get();
        return view('admin.complaint_form.list', compact('title', 'data', 'officer','roles','complaintformstatuses'));

    }
}