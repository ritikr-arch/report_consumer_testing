<?php

    

namespace App\Http\Controllers\Admin;

    

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\{User};



use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Arr;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\BaseController;

use Illuminate\Support\Facades\Validator;



use Spatie\Permission\Traits\RefreshesPermissionCache;

use Spatie\Permission\Traits\HasRoles;

    

    

class StaffController extends Controller

{

    use HasRoles;



    public function __construct (){

        // $this->middleware(['auth', 'AdminAuthenticate']);



        $this->middleware('permission:staff_list', ['only' => ['index']]);

        $this->middleware('permission:staff_create', ['only' => ['create', 'store']]);

        $this->middleware('permission:staff_edit', ['only' => ['edit', 'update', 'changeStatus']]);

        $this->middleware('permission:staff_view', ['only' => ['storeCompany']]);

        $this->middleware('permission:staff_delete', ['only' => ['delete']]);

    }





    public function index(Request $request){

        $title='Roles & Permission';

        $sub_title='Manage Staff';

        $roles = Role::select('id','name')->get();

        $roleArr = array();

        foreach($roles as $role){

           array_push($roleArr, $role->name); 

        }

        $users = User::with('roles')->orderBy('id','DESC')->get();

        

        return view('admin.users.index', compact('users', 'title', 'sub_title'));

    }

   

    // START FOR USER CREATE 

    public function create()

    {

        $title='Roles & Permission';

        $sub_title='Create Staff';

        $roles = Role::select('id','name')->get();

        return view('admin.users.create',compact('roles', 'title', 'sub_title'));

    }

    

    // END FOR USER CREATE 



    // START FOR INSERT USER 

    public function store(Request $request)

    {

        $validator = Validator::make($request->all(), [

            // 'fname'  => 'required',

            // 'lname'  => 'required',

            'fname' => [

                'required',

                'string',

                function ($attribute, $value, $fail) {

                    $filteredValue = preg_replace('/[^\&\(\)@\*\w\s\.,]+/', '', $value);

                    if ($value !== $filteredValue) {

                        $fail('The :attribute field should only contain &, (, ), @, *, ., text, numbers, comma!');

                    }

                },

            ],

            'lname' => [

                'required',

                'string',

                function ($attribute, $value, $fail) {

                    $filteredValue = preg_replace('/[^\&\(\)@\*\w\s\.,]+/', '', $value);

                    if ($value !== $filteredValue) {

                        $fail('The :attribute field should only contain &, (, ), @, *, ., text, numbers, comma!');

                    }

                },

            ],

            // 'email'          => 'required|email|unique:users,email',

            // 'mobile'         => 'required|numeric|digits:10|unique:users,mobile',

            'email' => [

                'required',

                'email',

                function ($attribute, $value, $fail) {

                    $filteredValue = preg_replace('/[^\&\(\)@\*\w\s\.,]+/', '', $value);

                    if ($value !== $filteredValue) {

                        $fail('The :attribute field should only contain &, (, ), @, *, ., text, numbers, comma!');

                    }

                },

                Rule::unique('users')->where(function ($query) {

                    $query->where('is_delete', 0); // Only consider records where is_delete is 0

                }),

            ],

            'mobile' => [

                'required',

                'numeric',

                'digits:10',

                function ($attribute, $value, $fail) {

                    $filteredValue = preg_replace('/[^\&\(\)@\*\w\s\.,]+/', '', $value);

                    if ($value !== $filteredValue) {

                        $fail('The :attribute field should only contain &, (, ), @, *, ., text, numbers, comma!');

                    }

                },

                Rule::unique('users')->where(function ($query) {

                    $query->where('is_delete', 0); // Only consider records where is_delete is 0

                }),

            ],

            'password'       => 'required',

            'country_id'  => 'required',

            'role'        => 'required',

        ]); 

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator->messages());

        }



        $roles = Role::where('id',$request->role)->first() ?? [];

        $role_name = $roles->name;



        $newUser = User::create([

            'name'      => $request->fname.' '. $request->lname,

            'fname'     => $request->fname,

            'lname'     => $request->lname,

            'email'     => $request->email,

            'mobile'    => $request->mobile,

            'country_id'=> $request->country_id,

            'password'  => bcrypt($request->password),

            'pass_'     => $request->password,

            'role'      => $request->role,

            'user_role'   => $role_name,

            'uuid'   => uniqueUserID(32),

            'created_at' => date('Y-m-d H:i:s'),

            'updated_at' => date('Y-m-d H:i:s')

        ]);



        $newUser->username = 'ARYAMRT00'.$newUser->id;

        $newUser->save();

        $newUser->assignRole($request->role);  



        $data = array(

            'name'      => $request->fname.' '. $request->lname,

            'fname'      => $request->fname,

            'lname'      => $request->lname,

            'email'      => $request->email,

            'mobile'      => $request->mobile,

            'address'      => '',

            'pass_'      => $request->password,

        );



        $html = view('auth.mail.user_detail',['data' => $data,true])->render();

        $this->sendMail($request->email, "Arya Marketplace - Register Details", $html);

        

        return redirect()->route('admin.users.index')->withSuccess('Staff created successfully');

    }



    public function edit($id)

    {

        $user = user::where('id',$id)->first() ?? []; 

        $roles = Role::select('id','name')->get();

        $userRole = $user->roles->pluck('id','name')->all();

        $title='Roles & Permission';

        $sub_title='Edit Staff';

        return view('admin.users.edit',compact('user', 'roles', 'userRole', 'title', 'sub_title'));



    }



    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [

            'name' => [

                'required'

            ],

            'role'          => 'required',

        ]); 

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator->messages());

        }



        // $input = $request->all();

        // $roles = Role::where('id',$request->role)->first() ?? [];

        // $role_name = $roles->name;

        // $input ['name'] = $request->fname.' '. $request->lname ;

        // $input ['mobile'] = $request->mobile;

        // $input ['country_id'] = $request->country_id;

        // $input ['role'] = $request->role;

        // $input ['user_role'] = $role_name;

        

        // $user = User::find($request->id);

        // $user->update($input);

        

        // DB::table('model_has_roles')->where('model_id',$id)->delete();

    

        // $user->assignRole($request->input('role'));



        $input = $request->all();



        // Retrieve role by ID

        $role = Role::find($request->role);



        if (!$role) {

            return redirect()->back()->withError('Invalid role selected.');

        }



        $role_name = $role->name;



        $input['name'] = $request->fname . ' ' . $request->lname;

        $input['mobile'] = $request->mobile;

        $input['country_id'] = $request->country_id;

        $input['role'] = $request->role;

        $input['user_role'] = $role_name;



        $user = User::find($request->id);

        $user->update($input);



        DB::table('model_has_roles')->where('model_id', $request->id)->delete();



        $user->assignRole($role_name);



        return redirect()->route('admin.staff.index')->withSuccess('Staff updated successfully !');

    }



    public function delete($id)

    {

        $changeStatus = User::find($id);

        $changeStatus->is_delete = '1';

        $delete = $changeStatus->save();

        if($delete){

            return response()->json(['status' => 'true','msg' => 'Staff deleted successfully','returnID' => $id]);

        }else{

            return response()->json(['status' => 'false','err' => 'Something went wrong!']);

        }

    }

    

    public function changeStatus(Request $request){

        

        $statuss = $request->status == 1 ? 0 : 1;

        $changeStatus = User::find($request->user_id);

        $changeStatus->status = $statuss;

        if($changeStatus->save()){

            return response()->json(['status' => 'true','status_type' => 'success','msg'=>'Status updated!']);

        } else {

            return response()->json(['status' => 'false','status_type' => 'error','msg'=>'Something went wrong!']);

        }

    }

    

}