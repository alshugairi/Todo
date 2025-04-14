<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('x-api-key');

        $validApiKey = '7MSxaS0Gqh1DTuaoURtKs3e8rfhG9Fn6BzvwFOcyraAP9X3G8IzpeJ25mv31uSTO';

        if ($apiKey !== $validApiKey) {
            return response()->json(['message' => __('share.unauthenticated')], 401);
        }

        return $next($request);
    }
}
