<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Models\TipAndAdice;

class TipsAndAdviceController extends Controller
{
    use ImageTrait;
    
    public function index(){

        $data = TipAndAdice::find(1);
        return view('admin.tip_advice.index', compact('data'));

    }

    public function update(Request $request){

        $this->validate($request, [
            // 'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            // 'title' => 'required|max:250',
            'description' => 'required',
        ]);
        // $aboutData = TipAndAdice::find($request->id) ?? new TipAndAdice();
        // if ($request->hasFile('imagess')) {
        //     $path = 'admin/images/cms/';
          
        //     if ($request->id && $aboutData->image) {
        //         $oldImagePath = public_path($path . $aboutData->image);
        //         if (file_exists($oldImagePath)) {
        //             unlink($oldImagePath);
        //         }
        //     }
        //     $image = $this->imageUpload($request->file('imagess'), $path);
        // }
        $id = ['id' => $request->id];
        $data = array(
            // 'title' => $request->title,
            'content' => $request->description,
            // 'image' =>   $image,
        );
        $result = TipAndAdice::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Tips and advice updated successfully.');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

        
    }
}
