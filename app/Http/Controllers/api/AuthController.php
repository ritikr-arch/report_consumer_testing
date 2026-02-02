<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserDevice;

class AuthController extends Controller
{


    /**
     * Login a user using email and password.
     *
     * @author Irfan Varis
     * @date   24-02-2025
     * @param Request $request
     *     - email (string): The user's email address.
     *     - password (string): The user's password.
     *     - device_token (string): The device token for push notifications.
     *     - device_type (string): The type of device ('ios', 'android', 'web').
     *
     * @return JsonResponse
     */
    public function login(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'email'        => 'required|email',
                'password'     => 'required',
                'device_token' => 'required|string',
                'device_type'  => 'required|string|in:ios,android,web',
            ]);

            $validRoles = ['compliance officer', 'investigation officer', 'chief investigation officer'];
            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return apiResponse(false, 'No account found with this email.', null,  404);
            }

            $roles = $user->getRoleNames();
            
            $userRole = (count($roles)>0)?$roles['0']:'';
            
            if(isset($userRole) && in_array(strtolower($userRole), $validRoles)){
                // return "This is valid role $userRole";
            }else{
                return apiResponse(false, "You're not authorised to login in mobile", null,  404);
            }

            if ($user->status != '1') {
                return apiResponse(false, 'Your account is not active. Please contact support.', null, 403);
            }


            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $tokenResult = $user->createToken('Laravel Password Grant Client');
                $token = $tokenResult->accessToken; 
                User::where('id', $user->id)->update(['is_first_login'=>false]);

                $device = UserDevice::updateOrCreate(
                    [
                        'user_id'      => $user->id,
                        'device_token' => $request->device_token,
                    ],
                    [
                        'device_type' => $request->device_type,
                    ]
                );
                $isLogin = ($user->is_first_login)?true:false;
                $role = strtolower($user->roles['0']->name);
                // return $user->roles['0']->name;

                return apiResponse(true, 'Login successful', ['token' => $token, 'is_first_login' => $isLogin, 'id'=>$user->id, 'role'=>$role]);
            } else {
                return apiResponse(false, 'Invalid credentials', null, 401);
            }
        }catch(\Exception $e){
            $msg = $e->getMessage();
            return apiResponse(false, $msg, null, 500);
        }
    }

    /**
     * Send a password reset link to the user's email.
     *
     *  @author Irfan Varis
     *  @date   24-02-2025
     * 
     * @param Request $request
     *     - email (string): The user's email address. Must exist in the users table.
     *
     * @return JsonResponse
     */
    public function sendResetLink(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ],[
                'email.exists' => "Email dosen't exist in our record",
            ]);

            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(10);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $otp, 'created_at' => Carbon::now()]
            );

            try {
                $user = User::where('email', $request->email)->first();
                Mail::to($user->email)->send(new SendOtpMail($otp, $user));
                return apiResponse(true, 'An OTP has been sent to your registered mail Id.', null, 200);
            }catch(\Exception $e){
                return apiResponse(false, 'Failed to send OTP. Try again later.', null, 500);
            }

            // $status = Password::sendResetLink($request->only('email'));
            
            // if ($status === Password::RESET_LINK_SENT) {
            //     return apiResponse(true, 'Password reset link sent successfully.', null, 200);
            // }
            return apiResponse(false, 'Failed to send reset link. Try again later.', null, 500);
        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Verify the OTP provided by the user.
     *
     * @author Irfan Varis
     * @date   24-02-2025
     *
     * @param Request $request
     *     - email (string): The user's email address. Must exist in the users table.
     *     - otp (numeric): The one-time password to be verified.
     *
     * @return JsonResponse
     */
    public function verifyOtp(Request $request){
        $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|numeric',
            ]);

        $otpRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        // if (!$otpRecord || Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()) {
        //     return response()->json(['success' => false, 'message' => 'Invalid OR Expired OTP.'], 400);
        // }
        if(!$otpRecord){
            return apiResponse(false, 'Invalid OTP.', null, 400);
        }
        if(Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()){
            return apiResponse(false, 'Your OTP has been expired.', null, 400);
        }

        DB::table('password_resets')->where('email', $request->email)->delete();
        return apiResponse(true, 'Your OTP has been verified.', null, 200);
    }

    /**
     * Reset the user's password.
     *
     * @author Irfan Varis
     * @date   24-02-2025
     *
     * @param Request $request
     *     - email (string): The user's email address. Must exist in the users table.
     *     - password (string): The new password which must be at least 8 characters long, confirmed, and alphanumeric (contain letters, special characters, and numbers).
     *
     * @return JsonResponse
     */
    public function reset(Request $request){
        try{
            $validation = Validator::make($request->all(), [
                'email'     => 'required|email|exists:users,email',
                // 'token'     => 'required',
                'password'  => 'required|string|min:8|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ], [
                'password.regex' => 'Password must be alphanumeric (Contain Letters,Special Charater and Numbers).',
            ]);

            if($validation->fails()){
                return apiResponse(false, 'Validation error', $validation->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
                return apiResponse(true, 'Password reset successfully.', null, 200);
            }else{
                return apiResponse(false, 'Invalid Email Id', null, 404);
            }
            // $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
            //     function (User $user, string $password) {
            //         $user->forceFill([
            //             'password' => Hash::make($password),
            //         ])->save();
            //     }
            // );

            if($status === Password::PASSWORD_RESET){
                return apiResponse(true, 'Password Reset Successfully', null, 200);
            }
            return apiResponse(false, 'Invalid Token OR Email', null, 400);

        }catch(\Exception $e){
            return apiResponse(false, $e->getMessage(), null, 500);
        }
    }    
    

}
