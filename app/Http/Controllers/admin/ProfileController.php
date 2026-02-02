<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  use ImageTrait;
  
    public function profile_view(){
      $user =  Auth::user();
      $data = User::with('userdetails')->find($user->id);
      return view('admin.user.profile', compact('data'));
    }

    public function profile_update(Request $request){
     $this->validate($request, [
        'name' => 'required|max:250',
        'email' => 'required|email|max:250',
        'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
    ]);
    $user =  Auth::user();

    if($request->hasFile('profile_image')){
      $oldImage = @$user->image;
      if($oldImage){
          $oldImagePath = public_path('admin/images/user/').$oldImage;

          if (file_exists($oldImagePath)) {
              unlink($oldImagePath);
          }
      }
      $image = $this->imageUpload($request->file('profile_image'), '/admin/images/user/');
      $data['image'] = $image;
      }else
       {
          $image = $user->image;
        }
                $data = array(
                  'name' => $request->name,
                  'email' => $request->email,
                  'image' => $image,
              );
              $data = $user->update($data);

              if($data){
                return redirect()->back()->withSuccess('Profile updated successfully');
              }else{
                return redirect()->back()->withError('Something went wrong. Please try later');
              }
  }

  public function change_password(){
    $user =  Auth::user();
    return view('admin.user.chanegePassword', compact('user'));
  }

public function update_password(Request $request)
{
    $request->validate([
        'old_password' => 'required|string|min:8|max:20',
        'password' => [
            'required',
            'string',
            'min:8',
            'max:15',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            function ($attribute, $value, $fail) use ($request) {
                if ($value === $request->old_password) {
                    $fail('The new password cannot be the same as the old password.');
                }
            },
        ],
    ]);

    $user = Auth::user();

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->withErrors(['old_password' => 'The old password is incorrect.']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Password updated successfully!');
}

  
  
}
