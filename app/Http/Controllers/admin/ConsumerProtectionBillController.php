<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Models\ConsumerProtectionBill;

class ConsumerProtectionBillController extends Controller
{
    use ImageTrait;
    
    public function index(){

        $data = ConsumerProtectionBill::find(1);
        return view('admin.ConsumerProtectionBill.index', compact('data'));

    }

    public function update(Request $request){

        $this->validate($request, [
            'imagess' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'title' => 'required|max:250',
            'content' => 'required',
        ]);
        $aboutData = ConsumerProtectionBill::find($request->id) ?? new ConsumerProtectionBill();
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
            'image' =>    $aboutData->image ?? null,
        );
        $result = ConsumerProtectionBill::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Consumer Protection Bill updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

        
    }
}
