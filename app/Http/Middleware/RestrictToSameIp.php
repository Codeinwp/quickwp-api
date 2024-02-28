<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictToSameIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( $request->getHost() !== parse_url( url()->current(), PHP_URL_HOST ) ) 
        {
            return response(
                [
                    'success' => false,
                    'message' => 'Access denied.'
                ],
                403
            );
        }

        return $next($request);
    }
}
