<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use App\Lib\Auth as Auth;

class AuthenticateApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!$request->hasHeader('Authorization')) {
            return response()->json([
                'error' => 'Authorization Header not found.'
            ], 401);
        }

        $token = $request->bearerToken();

        if($request->header('Authorization') == null || $token == null) {
            return response()->json([
                'error' => 'No token provided.'
            ], 401);
        }

        try {
            Auth::validateToken($token, Auth::PUBLIC_API_BASE);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return $next($request);
    }
}
