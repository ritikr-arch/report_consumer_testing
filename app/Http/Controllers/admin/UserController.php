<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ImageTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Models\User;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Exports\UserExport;

class UserController extends Controller
{

    use ImageTrait;
    public function __construct (){
        $this->middleware('permission:users_list', ['only' => ['index']]);
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:users_delete', ['only' => ['delete']]);
    }

    public function index(){
        $title = 'User List';
        $roles = Role::select('id','name')->get();
        $users = User::with('roles')->orderBy('id','DESC')->paginate(10);
        return view('admin.user.index', compact('users', 'title', 'roles'));
    }



    public function save(Request $request)
{
    $rules = [
        'title' => 'required',
        'name' => 'required|max:250',
        'role' => 'required',
        'status' => 'required|in:0,1',
    ];

    if (!$request->id) {
        $rules['email'] = 'required|email|regex:/^[\w\.\-]+@[a-zA-Z\d\-]+\.[a-zA-Z]{2,}$/|max:200';
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
    } else {
        $rules['email'] = 'required|email|regex:/^[\w\.\-]+@[a-zA-Z\d\-]+\.[a-zA-Z]{2,}$/|max:200|unique:users,email,'.$request->id;
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
    }

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $isNewUser = !$request->id;
    
    $pwd = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

    $user = User::find($request->id) ?? new User();

    $oldEmail = $user->email; // store old email before update

    $user->title = $request->title;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->status = $request->status;

    // Set password only for new user
    if ($isNewUser) {
        $user->password = Hash::make($pwd);
    }

    if ($request->hasFile('image')) {
        $path = 'admin/images/user/';
        if ($request->id && $user->image) {
            $oldImagePath = public_path($path . $user->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $image = $this->imageUpload($request->file('image'), $path);
        $user->image = $image;
    }

    $user->save();

    // Assign role
    $role = Role::find($request->role);
    if ($role) {
        $user->roles()->detach();
        $user->assignRole($role->name);

        if(!$isNewUser){
            activity()
                ->event('updated')
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties([
                    'new_roles' => $user->getRoleNames(),
                ])
                ->useLog('users')
                ->log("users role updated");
        }

        // Send password mail only if new user or email changed
        if ($isNewUser || $oldEmail !== $request->email) {
            $user->pwd = $pwd;
            Mail::to($user->email)->send(new SendPasswordMail($user));
        }

    } else {
        return response()->json([
            'success' => false,
            'message' => 'Invalid role selected.'
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => $isNewUser ? 'User added successfully.' : 'User updated successfully.',
    ]);
}


    public function edit($id){
        $data = User::with('roles')->find($id);
        if($data){
            if($data->image == NUll){
            $data->image = url('admin/images/user/default.png'); 
            }else{
            $data->image = url('admin/images/user/'.$data->image); 
            }
            
            return response()->json(['success' => true, 'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message'=>'No record found.']);
        }

    }

    public function delete($id){
        $user = User::find($id);
        if($user){
            if ($user->hasRole('admin')) {
                return response()->json(['success' => false, 'message'=>'Admin users cannot be deleted..']);
            }

            $image = @$user->image;
            if($image){
                $oldImagePath = public_path('admin/images/user/').$image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $user->delete();

            return response()->json(['success' => true, 'message'=>'User deleted successfully.']);
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }

    }



    public function updateStatus(Request $request){
        $market = User::find($request->id);
        if($market){
            $market->status = $request->status;
            $market->save();
            return response()->json(['success' => true, 'message'=>'Status updated successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }



    public function filter(Request $request){

        // dd($request->all());
        $roles = Role::select('id','name')->get();
        $title = 'User List';
        $query = User::with('roles');
        if($request->name){
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if($request->email){
            $query->where('email', 'like', '%' .$request->email. '%');
        }
        if(($request->status === '0') || ($request->status === '1') ){
            $query->where('status', $request->status);
        }

        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role);
            });

        }

        $users = $query->orderby('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.user.index', compact('title', 'roles', 'users'));

    }

    public function user_view($id){

        $data = User::with('roles')->find($id);
        if($data){
            return view('admin.user.view', compact('data'));
        }else{
            return redirect()->back()->withSuccess('Something went wrong. Please try later');
        }
    }

    public function exportUsers(Request $request)
    {
        $filters = $request->only(['name', 'email id', 'role', 'status']);
        return Excel::download(new UserExport($filters), 'users.xlsx');
    }

}

