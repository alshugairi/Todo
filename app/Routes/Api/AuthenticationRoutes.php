<?php

namespace App\Routes\Api;

use App\{Http\Controllers\Api\Authentication\AuthenticationController,
    Http\Controllers\Api\Authentication\ProfileController,
    Routes\Interfaces\RoutesInterface};
use Illuminate\Support\Facades\Route;

class AuthenticationRoutes implements RoutesInterface
{
    /**
     * @return void
     */
    public static function registerRoutes(): void
    {
        Route::group(attributes: [], routes: static function () {
            Route::post(uri: 'login', action: [AuthenticationController::class, 'login'])->middleware('throttle:5,1');
            Route::post(uri: 'register', action: [AuthenticationController::class, 'register']);
        });

        Route::group(attributes: ['middleware' => [
            'auth:sanctum',
        ]], routes: static function () {

            Route::group(attributes: ['middleware' => ['account.status']], routes: static function () {
                Route::post(uri: 'logout', action: [AuthenticationController::class, 'logout']);
                Route::get(uri: 'profile', action: [ProfileController::class, 'index']);
                Route::post(uri: 'profile', action: [ProfileController::class, 'update']);
            });
        });
    }
}
