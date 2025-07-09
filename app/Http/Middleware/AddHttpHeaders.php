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
            // $response->headers->remove('Cache-Control');
            // $response->headers->set('X-Content-Type-Options', 'nosniff');
            // $response->headers->set('X-Frame-Options', 'DENY');
            // $response->headers->set('Content-Security-Policy', "default-src 'self'");
            // $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            // $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "img-src 'self' data:; " .// for dropdown
            //"font-src 'self' data:; " .
            "style-src 'self' 'nonce-wUDPhZ1Z60inspnMCukimCi'; " .  // ✅ Nonce included here for apexchart
            "script-src 'self' 'nonce-wUDPhZ1Z60inspnMCukimCi'; " .
            "object-src 'none';"
        );
        }

        return $response;
    }
}