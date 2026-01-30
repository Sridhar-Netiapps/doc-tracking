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

    // public function getEncryptedAESKey()
    // {
    //     //gpt approach old
    //     $aesKey = bin2hex(random_bytes(16)); 
    //     $publicKey = Storage::get('keys/public_key.pem');
    //     session(['aes_key' => $aesKey]);
    //     openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey);
    //     return response()->json(['aes_key' => base64_encode($encryptedAESKey)]);
    // }

    // public function getEncryptedAESKey()
    // {
    //     $aesKey = random_bytes(16); // raw 128-bit AES key
    //     $publicKey = Storage::get('keys/public_key.pem');

    //     // Store AES key in session (Base64 encoded for safe storage)
    //     session(['aes_key' => base64_encode($aesKey)]);

    //     //gpt approach updated
    //     // Encrypt AES key with RSA (send to frontend if needed)
    //     openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey);
    //     // dd($encryptedAESKey);
    //     return response()->json([
    //         'aes_key_encrypted' => base64_encode($encryptedAESKey), // correct name
    //         'aes_key_base64'    => base64_encode($aesKey), // optional (for testing/debug only)
    //     ]);
    // }

    // public function authenticate(Request $request)
    // {
    //     // gpt approach updated
    //     // Get AES key from session
    //     $aesKeyBase64 = session('aes_key'); 
        
    //     if (!$aesKeyBase64) {
    //         return response()->json(['error' => 'Session AES key missing'], 400);
    //     }
    //     $aesKey = base64_decode($aesKeyBase64);
    //     // $publicKey = Storage::get('keys/public_key.pem');
    //     // openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey);

    //     // Decrypt password with AES
    //     $decryptedPassword = openssl_decrypt(
    //         base64_decode($request->password),
    //         'AES-128-ECB',
    //         $aesKey,
    //         OPENSSL_RAW_DATA
    //     );

    //     dd($decryptedPassword); // Debug
    // }

    // public function authenticate(Request $request)
    // {
    //     //gpt approach old
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);
    //     $encryptedAESKey = session('aes_key');

    //     if (!$encryptedAESKey) {
    //         return response()->json(['error' => 'AES key not found'], 400);
    //     }

    //     // Load the private key from storage
    //     // $privateKey = Storage::get('keys/private_key.pem');

    //     $privateKey = openssl_pkey_get_private(Storage::get('keys/private_key.pem'), env('AES_KEY'));

    //     // Decrypt AES key using RSA private key
    //     openssl_private_decrypt(base64_decode($encryptedAESKey), $decryptedAESKey, $privateKey);

    //     if (!$decryptedAESKey) {
    //         return response()->json(['error' => 'Failed to decrypt AES key'], 500);
    //     }

    //     // Decrypt the password using the AES key
    //     $encryptedPasswordBase64 = $request->password; // comes from frontend (Base64 string)
    //     $encryptedPassword = base64_decode($encryptedPasswordBase64);
    //     $decryptedPassword = Crypto::decrypt($encryptedPassword, Key::loadFromAsciiSafeString($decryptedAESKey));
    //     // $decryptedPassword = Crypto::decrypt(base64_decode($request->password), Key::loadFromAsciiSafeString($decryptedAESKey));
    //     dd($decryptedPassword);
    //     $username = $request->input('username');
    //     $password = $request->input('password');
        
    //     if(env('APP_ENV') != 'local'){
    //         try {
    //             $ldap = Container::getDefaultConnection();
    //             $ldap->connect();
    //             $isValidLdap = $ldap->auth()->attempt($username,$password);

    //             if ($isValidLdap) {
    //                 $user = User::where('employee_id', $username)->first();
    //                 if (!$user) {
    //                     return back()->withErrors(['username' => 'You are not authorized.']);
    //                 }
    //                 Session::flush();
    //                 Auth::logoutOtherDevices($password);
    //                 Auth::login($user);
    //                 if ($user->hasrole('super_admin')) {
    //                     return redirect()->route('users.index');
    //                 }
    //                 return redirect()->intended('/home');
    //             }
    //             return back()->withErrors(['username' => 'Invalid credentials.']);
    //         } catch (\Exception $e) {
    //             Log::error('LDAP Login Failed', ['error' => $e->getMessage()]);
    //         }
    //     }
    //     else{
    //         if (Auth::attempt(['employee_id' => $username, 'password' => $password])) {
    //             Auth::logoutOtherDevices($password);
    //             $user = Auth::user();
    //             if ($user->hasrole('super_admin')) {
    //                 return redirect()->route('users.index');
    //             }
    //             return redirect()->intended('/home');
    //         }
    //     }

    //     return back()->withErrors(['username' => 'Invalid credentials']);
    // }

    public function getEncryptedAESKey()
    {
        if (!Storage::exists('keys/public_key.pem')) {
            return response()->json(['error' => 'Public key not found.'], 500);
        }
        $publicKey = Storage::get('keys/public_key.pem');
        return response()->json(['public_key' => $publicKey]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'enc_aes_key' => 'required|string',
        ]);
        try {
            $privateKey = RSA::load(Storage::get('keys/private_key.pem'), config('app.private_key_passphrase'));
            $encryptedAesKey = base64_decode($request->input('enc_aes_key'));
            $decryptedAesKeyJson = $privateKey->withPadding(RSA::ENCRYPTION_PKCS1)->decrypt($encryptedAesKey);
            if (!$decryptedAesKeyJson) {
                throw new \Exception('Failed to decrypt AES key.');
            }
            $aesPayload = json_decode($decryptedAesKeyJson, true);
            $aesKey = base64_decode($aesPayload['key']);
            $aesIv = base64_decode($aesPayload['iv']);
            $aes = new AES('cbc'); 
            $aes->setKey($aesKey);
            $aes->setIV($aesIv);
            $encryptedPassword = base64_decode($request->input('password'));
            $decryptedPassword = $aes->decrypt($encryptedPassword);
            $decryptedPassword = rtrim($decryptedPassword, "\0");
        } catch (\Exception $e) {
            \Log::error('Login decryption failed: ' . $e->getMessage());
            return back()->withErrors(['username' => 'Login failed due to a security error.']);
        }
        $username = $request->input('username');
        $password = $decryptedPassword;
        if(env('APP_ENV') != 'local'){
            try {
                $ldap = Container::getDefaultConnection();
                $ldap->connect();
                $isValidLdap = $ldap->auth()->attempt($username,$password);
                $user = User::where('employee_id', $username)->first();

                if ($isValidLdap) {
                    // $user = User::where('employee_id', $username)->first();
                    if (!$user) {
                        return back()->withErrors(['username' => 'You are not authorized.']);
                    }
                    Session::flush();
                    // Auth::logoutOtherDevices($password);
                    Auth::login($user);
                    //  $user = Auth::user();
                     $user->session_id = Session::getId();
                     $user->save();


                    if ($user->hasrole('super_admin')) {
                        return redirect()->route('users.index');
                    }
                    if ($user->hasrole('ins-ho-user') || $user->hasrole('ins-admin')) {
                        return redirect()->route('insurance_dashboard');
                    }
                    return redirect()->intended('/home');
                } 
                // else {
                //     if ($user->hasRole('master')) {
                //         ActivityLog::create([
                //             'user_id' => $user->id,
                //             'event_type' => 'failed login',
                //             'description' => 'Invalid credentials.',
                //             'ip_address' => request()->ip(),
                //             'user_agent' => request()->userAgent(),
                //             'route' => request()->path(),
                //         ]);
                //     }
                //     return back()->withErrors(['username' => 'Invalid credentials.']);
                // }
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
