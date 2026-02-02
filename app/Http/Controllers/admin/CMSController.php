<?php

namespace App\Http\Controllers\admin;

use App\Models\CMS;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;

class CMSController extends Controller
{
    use ImageTrait;
    
    public function about_us(){
        $data = CMS::where('type', 'ABOUT_US')->first();
        return view('admin.cms.about_us', compact('data'));
    }

    public function about_update(Request $request)
    {
        $this->validate($request, [
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);
    
        $path = 'admin/images/cms/';
        $aboutData = CMS::find($request->id) ?? new CMS();
    
        // Delete old image if updating
        if ($request->hasFile('image') && $aboutData->image) {
            $oldImagePath = public_path($path . $aboutData->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    
        // Upload new image
        if ($request->hasFile('image')) {
            $aboutData->image = $this->imageUpload($request->file('image'), $path);
        }
    
        // Update or Create
        $result = CMS::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $request->title,
                'content' => $request->content,
                'type' => 'ABOUT_US',
                'image' => $aboutData->image ?? null,
               
            ]
        );
    
        if ($result) {
            return redirect()->back()->withSuccess('About Us updated successfully');
        } else {
            return redirect()->back()->withError('Something went wrong. Please try again later');
        }
    }
    
    

    public function Privacy_Policy(){
        $data = CMS::where('type', 'Privacy_Policy')->first();
        return view('admin.cms.privacy_policy', compact('data'));
    }


    public function Privacy_Policy_update (Request $request){

        $this->validate($request, [

            'content' => 'required',
        ]);
       
        $id = ['id' => $request->id];
        $data = array(
            'content' => $request->content,
            'type' => 'Privacy_Policy',
        );
        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Privacy Policy updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function terms_conditions(){

        $data = CMS::where('type', 'Terms_Conditions')->first();
        return view('admin.cms.terms_conditions', compact('data'));

    }

    public function terms_conditions_update (Request $request){

        $this->validate($request, [
            'content' => 'required',
        ]);
        $aboutData = CMS::find($request->id) ?? new CMS();

        $id = ['id' => $request->id];
        $data = array(
            'content' => $request->content,
            'type' => 'Terms_Conditions',
        );
        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Terms Conditions updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function our_mission(){
        $data = CMS::where('type', 'Our_Mission')->first();
        return view('admin.cms.our_mission', compact('data'));
    }

    public function our_mission_update (Request $request){

        $this->validate($request, [
            'imagess' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);
        $aboutData = CMS::find($request->id) ?? new CMS();


        $aboutData = CMS::find($request->id) ?? new CMS();
    
        // Delete old image if updating
        $path = 'admin/images/cms/';
        if ($request->hasFile('imagess') && $aboutData->image) {
            $oldImagePath = public_path($path . $aboutData->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

          // Upload new image
          if ($request->hasFile('imagess')) {
            $aboutData->image = $this->imageUpload($request->file('imagess'), $path);
        }

        $id = ['id' => $request->id];
        $data = array(
            'title' => $request->title,
            'content' => $request->content,
            'type' => 'Our_Mission',
            'image' =>    $aboutData->image ?? 'null',
        );
        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Mission Conditions updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function our_vision(){
        $data = CMS::where('type', 'Our_Vision')->first();
        return view('admin.cms.our_vision', compact('data'));

    }

    public function our_vision_update (Request $request){

        $this->validate($request, [
            'imagess' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);

        $aboutData = CMS::find($request->id) ?? new CMS();

        $path = 'admin/images/cms/';
        if ($request->hasFile('imagess') && $aboutData->image) {
            $oldImagePath = public_path($path . $aboutData->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

          // Upload new image
          if ($request->hasFile('imagess')) {
            $aboutData->image = $this->imageUpload($request->file('imagess'), $path);
        }

        $id = ['id' => $request->id];
        $data = array(
            'title' => $request->title,
            'content' => $request->content,
            'type' => 'Our_Vision',
            'image' =>   $aboutData->image ?? null,
        );
        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('vision updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function our_aim(){
        $data = CMS::where('type', 'Our_Aim')->first();
        return view('admin.cms.our_aim', compact('data'));

    }

    public function our_aim_update (Request $request){

        $this->validate($request, [
            'imagess' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);
        $aboutData = CMS::find($request->id) ?? new CMS();

        $path = 'admin/images/cms/';
        if ($request->hasFile('imagess') && $aboutData->image) {
            $oldImagePath = public_path($path . $aboutData->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

          // Upload new image
          if ($request->hasFile('imagess')) {
            $aboutData->image = $this->imageUpload($request->file('imagess'), $path);
        }



        $id = ['id' => $request->id];
        $data = array(
            'title' => $request->title,
            'content' => $request->content,
            'type' => 'Our_Aim',
            'image' =>  $aboutData->image,
        );
        $result = CMS::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Our Aim updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }


    

}