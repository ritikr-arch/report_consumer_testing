<?php

namespace App\Http\Controllers\admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Helpers\helpers;

class SettingController extends Controller

{
    use ImageTrait;

    public function __construct (){
        $this->middleware('permission:setting_list', ['only' => ['index']]);
        $this->middleware('permission:setting_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:setting_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:setting_delete', ['only' => ['delete']]);
    }

    public function index(){
        $data = Setting::find(1);
        return view('admin.setting.index', compact('data'));

    }

    public function updateSetting(Request $request){

        // dd($request->all());
        $this->validate($request, [
            'title' => 'required|max:200',
            'address' => 'required|max:200',
            'email' => 'required|email|regex:/^[\w\.\-]+@[a-zA-Z\d\-]+\.[a-zA-Z]{2,}$/|max:200',
            'phone' => 'required|max:150',
            'registration_number' => 'required|max:200',
            'port' => 'required|max:200',
            'user_name' => 'required|max:200',
            'password' => 'required|max:200',
            'host' => 'required|max:200',
            'facebook' => 'required|url|max:250',
            'twitter' => 'required|url|max:250',
            'instagram' => 'required|url|max:250',
            'linked' => 'required|url|max:250',
            'date_format' => 'required',
            'price_collection' => 'required|string|max:100',
            'admin_email' => 'required|string|email',
            'logo' => 'nullable|image|max:5120|dimensions:max_width=200,max_height=70',
            'favicon'    => 'nullable|image|max:5120|dimensions:max_width=64,max_height=64',

   
        ]);

        $updateSettings = Setting::find(1) ?? new Setting();
  
        if ($request->hasFile('logo')) {
            $path = 'admin/images/company_setting/';
            if ($request->id && $updateSettings->company_image) {
                $oldImagePath = public_path($path . $updateSettings->company_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $this->imageUpload($request->file('logo'), $path);
            $updateSettings->company_image = $image;
        }

        if ($request->hasFile('favicon')) {
            $path = 'admin/images/company_setting/';
            if ($request->id && $updateSettings->favicon) {
                $oldImagePath = public_path($path . $updateSettings->favicon);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $images = $this->imageUpload($request->file('favicon'), $path);
            $updateSettings->favicon = $images;
        }
            
            $updateSettings->company_title = $request->title;
            $updateSettings->company_address = $request->address;
            $updateSettings->company_registration_no = $request->registration_number;
            $updateSettings->phone = $request->phone;
            $updateSettings->email_address = $request->email;

            $updateSettings->port = $request->port;
            $updateSettings->user_name = $request->user_name;
            $updateSettings->password = $request->password;
            $updateSettings->host = $request->host;

            $updateSettings->social_fb = $request->facebook;
            $updateSettings->social_twitter = $request->twitter;
            $updateSettings->social_instagram = $request->instagram;
            $updateSettings->linked_in = $request->linked;
            $updateSettings->date_format = $request->date_format;
            $updateSettings->price_collection = $request->price_collection;
            $updateSettings->admin_email = $request->admin_email;
            $updateSettings->save();
            return redirect()->back()->withSuccess('Settings updated successfully');
      
   
}


}

