<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use App\Models\User;


use Auth;


class LoginController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Login Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles authenticating users for the application and

    | redirecting them to your home screen. The controller uses a trait

    | to conveniently provide its functionality to your applications.

    |

    */



    use AuthenticatesUsers;



    /**

     * Where to redirect users after login.

     *

     * @var string

     */

    // protected $redirectTo = '/home';

    protected $redirectTo = '/admin/dashboard';



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest')->except('logout');

        $this->middleware('auth')->only('logout');

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        if ($user->status != '1') {
            return back()->withErrors(['email' => 'Your account is not active.']);
        }

        // ✅ Check if remember is really being passed
        $remember = $request->filled('remember');


        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
         
            return redirect()->intended('admin/dashboard');
        }


        return back()->withErrors(['email' => 'Invalid email or password.']);
    }


}

