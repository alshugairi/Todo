<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if(session('locale') === NULL) {
            session()->put('locale', 'en');
        }

        app()->setLocale(session('locale'));

        if (\Illuminate\Support\Facades\Request::is('api/*') && $request->hasHeader('Accept-Language')) {
            app()->setLocale($request->header('Accept-Language'));
        }
        return $next($request);
    }
}
