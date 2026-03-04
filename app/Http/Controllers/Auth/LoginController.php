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
use Illuminate\Support\Facades\Crypt;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use Storage;
use \App\Models\ActivityLog;

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

    public function getEncryptedAESKey()
    {
        if (!Storage::exists('keys/public_key.pem')) {
            return response()->json(['error' => 'Public key not found.'], 500);
        }
        $publicKey = Storage::get('keys/public_key.pem');
        return response()->json([
            'public_key' => $publicKey,
        ]);
    }
    public function authenticate(Request $request) {
        $validated = $request->validate([
            'payload' => 'required|string',
        ]);
        
        try {
            $blob = json_decode(base64_decode($validated['payload']), true);
            $privateKey = RSA::load(Storage::get('keys/private_key.pem'), config('app.private_key_passphrase'));

            $aesKeyRaw = $privateKey->withPadding(RSA::ENCRYPTION_OAEP)->decrypt(base64_decode($blob['k']));

            $aes = new AES('gcm');
            $aes->setKey($aesKeyRaw);
            $aes->setNonce(base64_decode($blob['i']));
            $aes->setTag(substr(base64_decode($blob['p']), -16)); // GCM Tag is last 16 bytes
            
            $decrypted = $aes->decrypt(substr(base64_decode($blob['p']), 0, -16));
            $userData = json_decode($decrypted, true);
    
        } catch (\Exception $e) {
            \Log::error('VAPT Decryption Error: ' . $e->getMessage());
            return back()->withErrors(['username' => 'Security Handshake Failed.']);
        }
        $username = $userData['username'];
        $password = $userData['password'];
    
        if(env('APP_ENV') != 'local'){
            try {
                $ldap = Container::getDefaultConnection();
                $ldap->connect();
                if(env('APP_ENV') == 'production'){
                    $isValidLdap = $ldap->auth()->attempt($username.'@ujjivan.com',$password);
                }
                else{
                    $isValidLdap = $ldap->auth()->attempt($username,$password);
                }
                $user = User::where('employee_id', $username)->where('status', 'active')->first();

                if ($isValidLdap) {
                    if (!$user) {
                        return back()->withErrors(['username' => 'You are not authorized. Please contact ID Maintenance Team.']);
                    }
                    Session::flush();
                    Auth::login($user);
                     $user->session_id = Session::getId();
                     $user->save();
                    if ($user->hasrole('master')) {
                        return redirect()->intended('/home');
                    }
                    if ($user->hasrole('super_admin')) {
                        return redirect()->route('users.index');
                    }
                    if ($user->hasrole('ins-ho-user') || $user->hasrole('ins-admin')) {
                        return redirect()->route('insurance_dashboard');
                    }
                    return redirect()->intended('/home');
                } 
                else {
                    if (!$user->hasRole('master')) {
                        ActivityLog::create([
                            'user_id' => $user->id,
                            'event_type' => 'failed login',
                            'description' => 'Invalid credentials.',
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                            'route' => request()->path(),
                        ]);
                    }
                    return back()->withErrors(['username' => 'Invalid credentials.']);
                }
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
                
                if ($user->hasrole('master')) {
                    return redirect()->intended('/');
                }
                if ($user->hasrole('super_admin')) {
                    return redirect()->route('users.index');
                }
                if ($user->hasrole('ins-ho-user') || $user->hasrole('ins-admin')) {
                    return redirect()->route('insurance_dashboard');
                }
                return redirect()->intended('/');
            }
            else {
                $user = User::where('employee_id', $username)->first();
                
                if (!$user) {
                    ActivityLog::create([
                        'user_id' => 0,
                        'event_type' => 'failed login',
                        'description' => 'Invalid Employee id: '.$username,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'route' => request()->path(),
                    ]);
                    return back()->withErrors(['username' => 'Invalid Employee id: '.$username]);  
                }
                if (!$user->hasRole('master')) {
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'event_type' => 'failed login',
                        'description' => 'Invalid credentials.',
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'route' => request()->path(),
                    ]);
                }
                return back()->withErrors(['username' => 'Invalid credentials.']);
            }
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
