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
    // use ListensForLdapBindFailure;

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

    public function index()
    {
        return view('auth.login');
    }
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $username = $request->input('username');
        $password = $request->input('password');
        
        if(env('APP_ENV') != 'local'){
            try {
                $ldap = Container::getDefaultConnection();
                $ldap->connect();
                $isValidLdap = $ldap->auth()->attempt($username,$password);

                if ($isValidLdap) {
                    $user = User::where('employee_id', $username)->first();
                    if (!$user) {
                        return back()->withErrors(['username' => 'You are not authorized.']);
                    }
                    Session::flush();
                    // Auth::logoutOtherDevices($password);
                    Auth::login($user);
                    //  $user->session_id = Session::getId();
                    //  $user->save();
                    if ($user->hasrole('super_admin')) {
                        return redirect()->route('users.index');
                    }
                    return redirect()->intended('/home');
                }
                return back()->withErrors(['username' => 'Invalid credentials.']);
            } catch (\Exception $e) {
                Log::error('LDAP Login Failed', ['error' => $e->getMessage()]);
            }
        }
        else{
            if (Auth::attempt(['employee_id' => $username, 'password' => $password])) {
                // Auth::logoutOtherDevices($password);
                $user = Auth::user();
                $user->session_id = Session::getId();
                $user->save();
                // dd($user);
                if ($user->hasrole('super_admin')) {
                    return redirect()->route('users.index');
                }
                return redirect()->intended('/home');
            }
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
