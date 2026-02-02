<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Models\ConsumerEducationKids;

class ConsumerEducationKidsController extends Controller
{
    use ImageTrait;
    
    public function index(){
        $data = ConsumerEducationKids::find(1);
        return view('admin.consumer_education_kids.index', compact('data'));
    }

    public function update(Request $request){
        $this->validate($request, [
            'imagess' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);
        $aboutData = ConsumerEducationKids::find($request->id) ?? new ConsumerEducationKids();


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
            'image' =>   $aboutData->image ?? null,
        );
        $result = ConsumerEducationKids::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Consumer Education For Kids Program updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }
     
}
