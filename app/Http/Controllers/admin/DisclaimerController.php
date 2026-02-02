<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use App\Models\Disclaimer;

class DisclaimerController extends Controller
{
    use ImageTrait;
    
    public function index(){

        $data = Disclaimer::find(1);
        return view('admin.disclaimer.index', compact('data'));

    }

    public function update(Request $request){

        $this->validate($request, [
            'description' => 'required',
        ]);
       
        $id = ['id' => $request->id];
        $data = array(
            'content' => $request->description,
        );
        $result = Disclaimer::updateOrCreate($id, $data);
        if($result){
            return redirect()->back()->withSuccess('Disclaimer updated successfully');
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

        
    }
}
