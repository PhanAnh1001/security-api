<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use App\Lib\Auth as Auth;

class AuthenticateUser
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
          return response()->json('Authorization Header not found', 401);
        }

        $token = $request->bearerToken();

        if($request->header('Authorization') == null || $token == null) {
          return response()->json('No token provided', 401);
        }

        try {
            Auth::validateToken($token, Auth::PRIVATE_USER_API_BASE, $request->route('id'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return $next($request);
    }
}
