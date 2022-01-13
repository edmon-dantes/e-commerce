<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshTokenMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $this->checkForToken($request);

        return $next($request);
    }
}
