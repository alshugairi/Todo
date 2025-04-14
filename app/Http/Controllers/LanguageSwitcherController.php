<?php

namespace App\Http\Controllers;


use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageSwitcherController extends Controller
{
    /**
     * @param $locale
     * @return RedirectResponse
     */
    public function __invoke($locale): RedirectResponse
    {
        if (!array_key_exists($locale, config('laravellocalization.supportedLocales'))) {
            abort(400);
        }

        $previousUrl = url()->previous();
        $isAdminRoute = str_contains(url()->previous(), 'admin');

        $newUrl = LaravelLocalization::getLocalizedURL($locale, $previousUrl);

        app()->setLocale($locale);
        session()->put('locale', $locale);

        $headers = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];
        return $isAdminRoute ? back() : redirect($newUrl)->withHeaders($headers);
    }
}
