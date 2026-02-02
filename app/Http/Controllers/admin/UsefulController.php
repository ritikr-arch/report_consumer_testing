<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\UsefulModel;

class UsefulController extends Controller
{
    public function index()
    {
        $usefull_link = UsefulModel::orderBy('updated_at', 'desc')->first();
        // dd($usefull_link);
        return view('admin.useful_links.index', compact('usefull_link'));
    }

    public function edit(Request $request)
    {
        $type = $request->input('type');
        $content = UsefulModel::where('type',$type)->first();

        if(!empty($content))
        {
            $response=array();
            $response['status'] = '1';
            $response['content_id'] = $content['id'];
            $response['type'] = $content['type'];
            $response['title'] = $content['title'];
            $response['content'] = $content['content'];

            return response()->json($response);
        }
        else
        {
            $response=array();
            $response['status'] = '0';
            $response['message'] = 'There is no data found';

            return response()->json($response);
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'type' => 'required|in:1,2,3,4,5,6',
            'title' => 'required|string|max:50',
            'content' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        $response=array();
        $response['status'] = '0';
        $response['type'] = strip_tags($validator->errors()->first('type'));
        $response['title'] = strip_tags($validator->errors()->first('title'));
        $response['content'] = strip_tags($validator->errors()->first('content'));

        if ($validator->fails()) 
        {
            return response()->json($response);
        }
        else
        {
            $id = $request->input('id');

            $data=array();
            $data['type'] = $request->input('type');
            $data['title'] = $request->input('title');
            $data['content'] = $request->input('content');

            $useful = UsefulModel::updateOrCreate(['id' => $id], $data);

            $response=array();
            $response['status'] = '1';
            $response['message'] = 'Content updated successfully.';

            return response()->json($response);
        }
      
    }
}
