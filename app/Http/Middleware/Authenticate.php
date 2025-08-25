<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;
use Auth;
use Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        
        $this->authenticate($request, $guards);

        $user = $request->user();
       
        // Add debug logs
        \Log::debug('Current Session ID:', [$request->session()->getId()]);
        \Log::debug('Stored Session ID for User:', [$user->session_id]);
        //Check if the user is authenticated and has a session ID
        // print_r($user->session_id);
        // dd($request->session()->getId());
        if ($user && $user->session_id !== $request->session()->getId()) {
            \Log::debug('Session mismatch detected. Logging out user.');
            Session::flush();
            Auth::logout();
            return redirect('login')->withErrors(['username' => 'This User Logged in Another System']);
        }

        return $next($request);
    }
}
