<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Models\PublicHealthAct;

class PublicHealthActsController extends Controller
{
    use ImageTrait;
    
    public function index(){

        $data = PublicHealthAct::find(1);
        return view('admin.public_health_acts.index', compact('data'));

    }

    public function update(Request $request){

        $this->validate($request, [
            'images' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120|dimensions:max_width=600,max_height=400',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);


        $path = 'admin/images/cms/';
        $aboutData = PublicHealthAct::find($request->id) ?? new PublicHealthAct();
    
        // Delete old image if updating
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
            'image' => $aboutData->image ?? null,
        );
        $result = PublicHealthAct::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Public Health Act updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

        
    }

}
