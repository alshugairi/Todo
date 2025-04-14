<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTimeZone;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        config(['app.timezone' => 'UTC']);
        date_default_timezone_set('UTC');

        if (auth()->check()) {
            $user = auth()->user();
        }

        $user = null;
        if ($request->bearerToken()) {
            Auth::shouldUse('sanctum');
            $user = Auth::user();
        }

        if ($user) {
            $country = $user->country;
            $timezone = $country?->timezone;

            if ($timezone) {
                session(['user_timezone' => $timezone]);
            }
        }

        return $next($request);
    }
}
