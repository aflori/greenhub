<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/utility.php';


class IsAuthentifiedAsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if( $request->user() == null || //unconnected
            $request->user()->role !== "admin") {

            return getResponseMiddlewareError();
        }
        return $next($request);
    }
}
