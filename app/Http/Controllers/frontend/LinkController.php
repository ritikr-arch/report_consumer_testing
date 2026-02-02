<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\CMS;
use App\Models\FAQ;
use Carbon\Carbon;
use App\Models\Brouchers;
use App\Mail\ThankYouMail;
use App\Models\Disclaimer;

use App\Models\TipAndAdice;
use App\Models\ConsumerCorner;
use App\Models\ImageGallery;

use Illuminate\Http\Request;
use App\Models\NewsAndUpdate;
use App\Models\PublicHealthAct;

use App\Models\UsefulModel;

use App\Mail\ComplaintVerifyMail;
use App\Mail\ComplaintNotificationMail;
use App\Models\CustomerComplaint;
use Mews\Captcha\Facades\Captcha;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use App\Models\ConsumerEducationKids;
use App\Models\ConsumerProtectionBill;
use App\Models\ComplaintDocument;
use App\Models\ComplaintFormStatus;
use App\Models\ComplaintCategory;
use App\Models\TipsAdvice;

class LinkController extends Controller
{
    public function educationProgram()
    {
        $title = 'Education Program for Kids';
        $education = ConsumerEducationKids::first();
        return view('frontend.links.educationProgram', compact('title','education'));
    }

    public function faq()
    {
        $title = 'Question of the week';
        $faq = FAQ::where('status',1)->orderBy('id','desc')->get();
        return view('frontend.links.consumerCorner.faq', compact('title','faq'));
    }

    public function brochures()
    {
        $title = 'Brochures';
        $brouchers = Brouchers::where(['status' => '1', 'type' => 'Brochures'])->orderBy('id','desc')->get();
        return view('frontend.links.publications.brochure', compact('title','brouchers'));  
    }

    public function articles()
    {
        $title = 'Articles';
        $article = NewsAndUpdate::where(['status' => '1','type' => 'article'])->orderBy('id','desc')->get();
        return view('frontend.links.publications.articles', compact('title','article'));  
    }

    public function articleDetails($id)
    {
        $title = 'Articles Details';
        return view('frontend.links.publications.artcleDetails', compact('title'));
    }

    public function article_detail($slug)
    {
        $article = NewsAndUpdate::where('slug',$slug)->first();
        $articles = NewsAndUpdate::where(['status' => '1','type' => 'article'])->orderBy('id','desc')->get();
        return view('frontend.links.publications.articledetail',compact('article','articles')); 
    }

    public function presentations()
    {
        $title = 'Presentations';
        $presentation = Brouchers::where(['status' => '1','type' => 'presentation'])->orderBy('id','desc')->get();
        return view('frontend.links.publications.presentations', compact('title','presentation'));  
    }

    public function tipsAdvice()
    {
        $tipadice = TipAndAdice::where(['status' => '1'])->orderBy('id','desc')->first();
        $title = 'Tips and advice';
        return view('frontend.links.consumerCorner.tips', compact('tipadice', 'title' ));
    }

    public function consumerProtectionBill()
    {
        $title = 'Consumer Protection Bill';
        $protection = ConsumerProtectionBill::first();
        return view('frontend.links.consumerProtectionBill', compact('title','protection'));
    }

    public function publicHealthAct()
    {
        $title = 'Public Health Act';
        $health = PublicHealthAct::first();
        return view('frontend.links.publicHealthAct', compact('title','health'));
    }

    public function disclaimers(){

        $title = 'disclaimers';
        $disclaimer = Disclaimer::first();
        return view('frontend.links.consumerCorner.disclaimer', compact('title','disclaimer'));
    }

    public function privacy()
    {
        $title = 'privacy policy';
        $privacy = CMS::where('type','Privacy_Policy')->first();
        return view('frontend.privacy', compact('title','privacy'));
    }

    public function terms()
    {
        $title = 'terms & condition';
        $terms = CMS::where('type','Terms_Conditions')->first();
        return view('frontend.terms', compact('title','terms'));
    }

    public function imageGallery()
    {
        $title = 'Image Gallery';
        $image = ImageGallery::with('multiImages')->where('status','1')->orderBy('id','desc')->get();
       
        return view('frontend.links.imageGallery', compact('title','image'));
    }

    public function complaintForm()
    {
        $title = 'Complaint Form';
        $complaint = '';
        return view('frontend.links.complaintForm', compact('title','complaint'));
    }

    public function blogs(){
        $title = 'Blogs';
        $blog = NewsAndUpdate::where(['status' => '1','type' => 'blog'])->orderBy('id','desc')->get();
        return view('frontend.links.publications.blog', compact('title','blog'));  
        
    } 

    public function press_release(){
        $title = 'Tips And Advice';
        $press = TipsAdvice::where('status', '1')->orderBy('id','desc')->get();
        return view('frontend.links.publications.press', compact('title','press'));  
    }

    public function press_release_detaails($slug){
        $title = 'Tips And Advice';
        $press = TipsAdvice::where('id',$slug)->first();
        $press_release = TipsAdvice::orderBy('id','desc')->where('status', '1')->paginate(5);
        if($press && $press_release){
        return view('frontend.links.publications.press_details', compact('title','press', 'press_release'));
        }else{
            return redirect('/');
        }
          
    }

    public function consumerCorner(){
        $title = 'Consumer Corner';
        $consumer_corner = ConsumerCorner::where('status','1')->orderBy('id','desc')->get();
        return view('frontend.links.publications.consumer_corner', compact('title', 'consumer_corner'));  

    }

    public function lid()
    {
        $title = 'Lid On Spending';
        $content = UsefulModel::where('type','1')->first();
        return view('frontend.links.lid', compact('title','content'));
    }

    public function cellular()
    {
        $title = 'Cellular Phones';
        $content = UsefulModel::where('type','2')->first();
        return view('frontend.links.cellular', compact('title','content'));
    }

    public function rights()
    {
        $title = 'Consumer Right & Responsibilites';
        $content = UsefulModel::where('type','3')->first();
        return view('frontend.links.rights', compact('title','content'));
    }

    public function backyard()
    {
        $title = 'Backyard Gardening';
        $content = UsefulModel::where('type','4')->first();
        return view('frontend.links.backyard', compact('title','content'));
    }

    public function weight()
    {
        $title = 'Weight Measure';
        $content = UsefulModel::where('type','5')->first();
        return view('frontend.links.weight', compact('title','content'));
    }

    public function wise()
    {
        $title = 'Wise Spender';
        $content = UsefulModel::where('type','6')->first();
        return view('frontend.links.wise', compact('title','content'));
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

    public function complaint_form_process(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'first_name' => 'required|max:50|string',
                'email' => 'required|email|max:255',
                'country_code' => 'required',
                'phone_no' => 'required|numeric|digits_between:7,10',
                'address' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female', 
                'age_group' => 'required',
                'g-recaptcha-response' => 'required|captcha'
            ], [ 
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA to proceed.',
                'g-recaptcha-response.captcha' => 'Invalid reCAPTCHA response. Please try again.',
                'country_code.required' => 'The Country Code field is required.',
            ]);

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
            $data['created_at'] = date('Y-m-d h:i:s');

            $mail = array();
            $mail['name'] = $request->input('first_name').' '.$request->input('last_name');
            $mail['path'] = route('frontend.complete-your-profile', ['id' => base64_encode($this->generate_customer_id())]);

            $complaint='';

            Mail::to($request->input('email'))->send(new ComplaintVerifyMail($mail));
            $complaintdata = CustomerComplaint::create($data);
            Mail::to(adminEmail())->send(new ComplaintNotificationMail($complaintdata));

            return redirect()->route('frontend.complaint.form',compact('complaint'))->with('success_message','Thank you for submitting your complaint. A link to complete the full complaint form has been sent to your email. Please check your inbox.');
        }
    }

    public function complaint_verify_email($id)
    {
        $id = base64_decode($id);
        $complaint = CustomerComplaint::where('complaint_id',$id)->first();

        if (!$complaint) 
        {
            return redirect()->route('frontend.complaint.form')->with('error_message', 'Complaint not found.');
        }

        if ($complaint->is_completed == 0) 
        {
            return redirect()->route('frontend.complaint-complete-form', ['id' => $complaint->complaint_id]);
        } 
        else 
        {
            return redirect()->route('frontend.complaint.form')->with('error_message', 'A complaint with these details has already been submitted. The Complaint ID has been sent to your email. Please enter it in the Track Your Complaint box to monitor progress..');
        }
    }

    public function complaint_complete_form($id)
    {
        $complaint = CustomerComplaint::orderBy('id','desc')->where('complaint_id',$id)->first();
        $complaintCategory = ComplaintCategory::where('status', 1)->orderby('id', 'desc')->get(); 
        return view('frontend.links.complaint_complete_form',compact('complaint','complaintCategory'));
    }

    public function complaint_complete_form_process(Request $request)
    {
        if($request->isMethod('post'))
        {
            $complaint = CustomerComplaint::where('complaint_id',$request->input('complaint_id'))->first();

            if (!$complaint) 
            {
                return redirect()->route('frontend.complaint.form')->with('error_message', 'Complaint not found.');
            }

            if ($complaint->is_completed == 1) 
            {
                return redirect()->route('frontend.complaint.form')->with('error_message', 'A complaint with these details has already been submitted. The Complaint ID has been sent to your email. Please enter it in the Track Your Complaint box to monitor progress..');
            }

            $request->validate([
                'business_name' => 'required|string|max:50', 
                'business_email' => 'required|email:rfc,filter|max:150',
                'business_country_code' => 'required',
                'business_phone' => 'required|numeric|digits_between:7,10', 
                'business_address' => 'required|string|max:250',
                'goods' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'serial' => 'required|string|max:50',
                'category' => 'required|string|max:50', 
                'date_purchase' => 'required|date', 
                'warranty' => 'required|string|max:50', 
                'hire_purchase' => 'required|in:0,1',
                'contract' => 'required|in:0,1',
                'additional_statement' => 'nullable|string|max:500',
                'certify' => 'required',
                'documents' => 'required|array',
                'documents.*' => 'file|max:2048|mimes:jpg,png,docx,pdf,doc',
                'date' => 'required|date', 
                 'g-recaptcha-response' => 'required|captcha'
            ], [ 
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA to proceed.',
                'g-recaptcha-response.captcha' => 'Invalid reCAPTCHA response. Please try again.',
                'business_country_code.required' => 'The Bussiness Country Code field is required.',
            ]);

            // // Signature
            // $image = $request->input('signature_image');
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);
            // $imageData = base64_decode($image);

            // $fileName = 'signatures/' . uniqid() . '.png';
            // Storage::disk('public')->put($fileName, $imageData);

            // Get the base64 image from input
                $image = $request->input('signature_image');

                // Remove the base64 prefix and prepare for decoding
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                // Define the destination path in the public directory
                $destination = public_path('signatures');

                // Create the folder if it doesn't exist
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                // Generate a unique file name
                $fileName = 'signature_' . uniqid() . '.png';
                $fullPath = $destination . '/' . $fileName;

                // Save the decoded image to the file
                file_put_contents($fullPath, $imageData);
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
            $data['business_name'] = $request->input('business_name');
            $data['business_address'] = $request->input('business_address');
            $data['business_country_code'] = $request->input('business_country_code');
            $data['business_phone'] = $request->input('business_phone');
            $data['business_email'] = $request->input('business_email');
            $data['service'] = $request->input('goods');
            $data['serial'] = $request->input('serial');
            $data['category'] = $request->input('category');
            $data['date_of_purchase'] = $request->input('date_purchase');
            $data['warranty'] = $request->input('warranty');
            $data['brand'] = $request->input('brand');
            $data['hire_purchase_item'] = $request->input('hire_purchase');
            $data['sign_contract'] = $request->input('contract');
            $data['additional_statement'] = $request->input('additional_statement');
            $data['signed'] = 'signatures/'.$fileName;
            $data['date'] = $request->input('date');
            $data['email_verified'] = '1';
            $data['is_completed'] = '1';
            $data['created_at'] = date('Y-m-d h:i:s');
            $data['updated_at'] = date('Y-m-d h:i:s');

            $user = CustomerComplaint::orderBy('id','desc')->where('email',$request->input('email'))->first();

            $mail=array();
            $mail['name'] = isset($user['first_name']) ? $user['first_name'].' '.$user['last_name'] : '';
            $mail['complaint_id'] = isset($user['complaint_id']) ? $user['complaint_id'] : '';

           if (!empty($request->file('documents'))) {
    foreach ($request->file('documents') as $document) {
        $originalName = $document->getClientOriginalName();
        $fileType = $document->getClientOriginalExtension();
        $fileSize = $document->getSize();

        $filename = Str::random(10) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
        $destination = public_path('documents');

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $document->move($destination, $filename);

        $file = [
            'complaint_id' => $user->id,
            'document' => 'documents/' . $filename,
            'created_at' => now(),
        ];

        ComplaintDocument::create($file);
    }
}


            Mail::to($request->input('email'))->send(new ThankYouMail($mail));
            CustomerComplaint::where('complaint_id',$request->input('complaint_id'))->update($data);
            return redirect()->route('frontend.complaint.form')->with('success_message','Thank you! Your complaint has been submitted successfully. We will get back to you soon.');
        }
    }

    public function search_complaint(Request $request)
{
    if ($request->isMethod('get')) {
        $request->validate([
            'complaint_id' => 'required|min:6|max:17|alpha_num',
        ]);

        $complaint_prefix = 'CID';
        $complaint_id = preg_replace('/^CID/', '', $request->complaint_id);
        $complaint = CustomerComplaint::where('complaint_id', $complaint_id)->first();

        // 🛡️ Check if complaint exists before accessing its properties
        if ($complaint) {
            $complaintformstatuses = ComplaintFormStatus::where('complaints_id', $complaint->id)
                ->where('status', '!=', '')
                ->orderBy('id', 'desc')
                ->get();

            $isclosed = ComplaintFormStatus::where('complaints_id', $complaint->id)
                ->where('status', '4')
                ->first();
        
            if(count($complaintformstatuses) > 0){
            return view('frontend.links.complaintFormSearch', compact('complaint', 'complaintformstatuses', 'isclosed'));
            }else{
                return redirect()->route('frontend.complaint.form')
                ->with('error_search_complaint', 'No action taken on this complaint yet.');
            }

            
        } else {
            return redirect()->route('frontend.complaint.form')
                ->with('error_search_complaint', 'The complaint ID does not exist.');
        }
    }
}

}
