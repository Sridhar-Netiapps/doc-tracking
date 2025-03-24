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
            $response->headers->remove('Cache-Control');
        }

        return $response;
    }
}