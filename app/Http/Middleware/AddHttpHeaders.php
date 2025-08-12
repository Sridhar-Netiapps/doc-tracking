<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddHttpHeaders
{
    /**
     * Add various HTTP headers to the application responses.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle($request, Closure $next): Response
    {
        $response = $next($request);
        if($response instanceof Response){
           /* $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');*/
            
        /*$response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "img-src 'self' data:; " .// for dropdown
            //"font-src 'self' data:; " .
            "style-src 'self' 'nonce-wUDPhZ1Z60inspnMCukimCi'; " .  // ✅ Nonce included here for apexchart
            "script-src 'self' 'nonce-wUDPhZ1Z60inspnMCukimCi'; " .
            "object-src 'none';"
        );*/
        }

        return $response;
    }
}