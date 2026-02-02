<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
    
class RoleController extends Controller
{

    public function __construct (){
        // $this->middleware(['auth', 'AdminAuthenticate']);

        $this->middleware('permission:List', ['only' => ['index', 'show']]);
        $this->middleware('permission:View', ['only' => ['index','show']]);
        $this->middleware('permission:Edit', ['only' => ['edit','update']]);
        $this->middleware('permission:Create', ['only' => ['create','store']]);
        $this->middleware('permission:Delete', ['only' => ['delete']]);
    }
  
    public function index(Request $request)
    {

        // $roles = Role::whereNot('id','2')->orderBy('id','DESC')->get() ?? [];
        $title='Roles & Permission';
        $roles = Role::orderBy('id','DESC')->paginate(10) ?? [];
        $permission = Permission::get();
        return view('roles.index',compact('roles','title', 'permission'));
    }
    
    
    public function create()
    {
        $title='Roles & Permission';
        $sub_title='Role';
        $permission = Permission::get();
        return view('roles.create',compact('permission', 'title', 'sub_title'));
    }
    
  
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $role = Role::create(['name' => $request->input('name')]);
        $permissions = Permission::whereIn('id', $request->input('permission'))->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        activity('role')
            ->event('created')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties(['name' => $role->name])
            ->log("created");
    
        return redirect()->route('admin.role.index')->withsSuccess('Role created successfully');
    }
  
    public function show($id)   
    {
        $title='Roles & Permission';
        $sub_title='View Role';
        $role = Role::find($id);
        $permission = Permission::get();
        // $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        //     ->where("role_has_permissions.role_id",$id)
        //     ->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
        return view('roles.show',compact('role','permission', 'rolePermissions', 'title', 'sub_title'));
    }

    public function edit($id)
    {
        $title='Roles & Permission';
        $sub_title='Edit Role';
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions', 'title', 'sub_title'));
    }
    
    // public function update(Request $request, $id){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'permission' => 'required',
    //     ]);
    //     if($validator->fails()) {
    //         return redirect()->back()->withErrors($validator->messages());
    //     }
    //     $role = Role::find($request->id);
    //     $role->name = $request->input('name');
    //     $role->save();
    //     $permissionNames = Permission::whereIn('id', $request->input('permission'))->pluck('name')->toArray();
    //     $role->syncPermissions($permissionNames);

    //     return redirect()->route('admin.role.index')->withSuccess('Role updated successfully');
    // }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator->messages());
        }

        // Find the role by ID
        $role = Role::find($id);

        if (!$role) 
        {
            return redirect()->back()->withError('Role not found.');
        }

        $oldName = $role->name;
        // Update role name
        $role->name = $request->input('name');
        $role->save();

        // Sync permissions
        $permissionNames = Permission::whereIn('id', $request->input('permission'))->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);

        // Find users with this role and update their roles
        $users = User::role($role->name)->get();
        foreach ($users as $user) 
        {
            $user->syncRoles([$role->name]); // Removes previous roles and assigns the updated role
        }

        activity('role')
            ->event('updated')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'old_name' => $oldName,
                'new_name' => $role->name,
            ])
            ->log("updated");

        return redirect()->route('admin.role.index')->withSuccess('Role updated successfully');
    }

   
    public function delete($id){
        $role = Role::find($id);
        if($role){
            if (strtolower($role->name) === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'The Admin role cannot be deleted.'
                ]);
            }
            $role->delete();

            activity('role')
                ->event('deleted')
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->withProperties(['name' => $role->name])
                ->log("deleted");

            return response()->json(['success' => true, 'message'=>'Role deleted successfully.']);
        }else{
            return response()->json(['success' => false, 'message'=>'Something went wrong.']);
        }
    }

    public function module_list(){
        $title='Roles & Permission';
        $sub_title='Modules';
        $permission = Permission::paginate(50)->withQueryString() ?? [];
        return view('admin.role.table', compact('permission','title', 'sub_title'));
    }

    public function module_create()
    {
        $title='Roles & Permission';
        $sub_title='Add Module';
        return view('admin.role.add', compact('title', 'sub_title'));
    }

    public function module_store(Request $request)
    {
        $title='Roles & Permission';
        $sub_title='Modules';
        $validator = Validator::make($request->all(), [
            'module_name' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->messages());
        }
        
        $module = Permission::create(['name' => convertToText($request->input('module_name')).'_list']);
        $module = Permission::create(['name' => convertToText($request->input('module_name')).'_create']);
        $module = Permission::create(['name' => convertToText($request->input('module_name')).'_edit']);
        $module = Permission::create(['name' => convertToText($request->input('module_name')).'_delete']);
        $module = Permission::create(['name' => convertToText($request->input('module_name')).'_view']);
        // return view('roles.table', compact('title', 'sub_title'));
        if($module){
            return response()->json(['status' => 'true','msg' => 'Module created successfully']);
        } else {
            return response()->json(['status' => 'false','err' => 'Something went wrong!']);
        }
    
    }

    public function module_edit($id)
    {
        $title='Roles & Permission';
        $sub_title='Edit Module';
        $permission = Permission::find($id);
        $html = view('admin.role.update_module',['permission' => $permission, 'title' => $title, 'sub_title' => $sub_title, true])->render();
        return response()->json(['status' => 'success','html' => $html]);
    }
    
    public function module_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'module_name' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json($validator->messages());
        }
        $permission = Permission::find($request->id);
        $oldPermissionName = explode('_',$permission->name);//gets old permission name

        //replace old permission name with new name appending _list, _create, _edit, _delete, _view.
        $permissionList = Permission::where('name', $oldPermissionName[0].'_list')->update(['name'=>convertToText($request->input('module_name')).'_list']);
        $permissionCreate = Permission::where('name', $oldPermissionName[0].'_create')->update(['name'=>convertToText($request->input('module_name')).'_create']);
        $permissionEdit = Permission::where('name', $oldPermissionName[0].'_edit')->update(['name'=>convertToText($request->input('module_name')).'_edit']);
        $permissionDelete = Permission::where('name', $oldPermissionName[0].'_delete')->update(['name'=>convertToText($request->input('module_name')).'_delete']);
        $permissionView = Permission::where('name', $oldPermissionName[0].'_view')->update(['name'=>convertToText($request->input('module_name')).'_view']);
        
        if($permissionView){
            return response()->json(['status' => 'true','msg' => 'Module successfully saved!']);
        } else {
            return response()->json(['status' => 'false','err' => 'Something went wrong!']);
        }
    }
   
    public function module_delete(Request $request, $id)
    {
        $moduleNameList = convertToText($request->input('module_name')).'_list';
        $moduleNameCreate = convertToText($request->input('module_name')).'_create';
        $moduleNameEdit = convertToText($request->input('module_name')).'_edit';
        $moduleNameDelete = convertToText($request->input('module_name')).'_delete';
        $moduleNameView = convertToText($request->input('module_name')).'_view';
        // echo $moduleNameList;die;
        // DB::table("permissions")->where('id',$id)->delete();
        DB::table("permissions")->where('name',$moduleNameList)->delete();
        DB::table("permissions")->where('name',$moduleNameCreate)->delete();
        DB::table("permissions")->where('name',$moduleNameEdit)->delete();
        DB::table("permissions")->where('name',$moduleNameDelete)->delete();
        DB::table("permissions")->where('name',$moduleNameView)->delete();
        return response()->json(['status' => 'true','msg' => 'Module deleted successfully','returnID' => $id]);
    }

}