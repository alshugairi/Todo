<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class EnsureSessionId
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('session_id')) {
            $request->session()->put('session_id', Str::uuid());
        }
        return $next($request);
    }
}
