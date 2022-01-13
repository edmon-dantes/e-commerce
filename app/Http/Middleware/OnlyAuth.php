<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $request->merge(['_user' => auth()->id()]);
        return $next($request);
    }
}
