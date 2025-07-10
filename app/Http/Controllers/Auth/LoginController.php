<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\LoginAudit;
use LdapRecord\Container;

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
    use ListensForLdapBindFailure;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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

    // public function index()
    // {
    //     return view('auth.login');
    // }
    // public function authenticate(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string|max:50',
    //         'password' => 'required|string|min:4|max:50',
    //     ]);

    //     $username = $request->username;
    //     $password = $request->password;

    //     try {
    //         $ldap = Container::getDefaultConnection();
    //         $isValidLdap = $ldap->auth()->attempt("uid={$username},dc=example,dc=com", $password);

    //         if ($isValidLdap) {
    //             $user = User::where('username', $username)->first();

    //             if (!$user) {
    //                 return back()->withErrors(['username' => 'You are not authorized.']);
    //             }

    //             // Invalidate previous sessions
    //             Session::flush();
    //             Auth::logoutOtherDevices($password);

    //             Auth::login($user);

    //             return redirect()->intended('/dashboard');
    //         }

    //         return back()->withErrors(['username' => 'Invalid credentials.']);
    //     } catch (\Exception $e) {
    //         Log::error('LDAP Login Failed', ['error' => $e->getMessage()]);
    //     }
    //         if (Auth::attempt(['username' => $username, 'password' => $password])) {
    //             $user = Auth::user();
    //             $this->logAudit($user, $request);
    //             return redirect()->intended('/dashboard');
    //         }
    //         return back()->withErrors(['username' => 'LDAP server error.']);
    // }

    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     Session::flush();
    //     return redirect('/login');
    // }
}
