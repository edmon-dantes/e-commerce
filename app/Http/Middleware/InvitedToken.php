<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class InvitedToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!JWTAuth::getToken()) {
            $request->merge(['username' => 'invited', 'password' => 'abc123ABC']);
            $jwt_token = JWTAuth::customClaims(['token' => Str::random(60)])->attempt(request()->only(['username', 'password']));
            $request->headers->set('Authorization', 'Bearer ' . $jwt_token);
        }
        return $next($request);
    }
}
