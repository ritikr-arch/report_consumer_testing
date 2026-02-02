<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\Enquiry;
use App\Models\EnquiryCategory;
use App\Mail\EnquiryThankYouMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminEnquiryNotificationMail;

class ContactController extends Controller
{
    public function index()
    {
        $title = 'Contact Us';
        $categories = EnquiryCategory::where(['status'=>'1'])->orderby('name', 'asc')->get();
        $setting = Setting::find(1);
        return view('frontend.contact', compact('title','setting', 'categories'));
    }

    public function send_message(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|max:150',
                'country_code' => 'required',
                'phone' => 'required|numeric|digits_between:7,10',  
                'message' => 'required|string',
                'category' => 'required'
            ]);

            $data = array();
            $data['name'] = $request->input('name');
            $data['category_id'] = $request->input('category');
            $data['email'] = $request->input('email');
            $data['country_code'] = $request->input('country_code'); 
            $data['phone'] = $request->input('phone'); 
            $data['message'] = $request->input('message');
            $data['type'] = 'General';
            $data['created_at'] = date('Y-m-d H:i:s');  

            Enquiry::create($data);
            Mail::to($data['email'])->send(new EnquiryThankYouMail($data['name'], 'getintouch'));
            Mail::to(adminEmail())->send(new AdminEnquiryNotificationMail($data));
            return redirect()->route('frontend.contact')->with('success', 'Thanks for contacting us! We’ll review your message and respond shortly.');
        }
    }
}
