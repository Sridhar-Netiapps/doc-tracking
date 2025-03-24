<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * Set to 'false' to prevent exposing the CSRF token in cookies.
     *
     * @var bool
     */
    protected $addHttpCookie = false;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add URIs here to skip CSRF protection
    ];

    /**
     * Add the CSRF token to the response cookies if enabled.
     *
     * @param  Request  $request
     * @param  Response  $response
     * @return Response
     */
    // protected function addCookieToResponse($request, $response)
    // {
    //     return parent::addCookieToResponse($request, $response);
    // }
}
