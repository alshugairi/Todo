<?php

namespace App\Routes\Api;

use App\{Http\Controllers\Api\TaskController,
    Routes\Interfaces\RoutesInterface};
use Illuminate\Support\Facades\Route;

class TaskRoutes implements RoutesInterface
{
    /**
     * @return void
     */
    public static function registerRoutes(): void
    {
        Route::group(attributes: ['middleware' => ['auth:sanctum']], routes: static function () {

//            Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);
            Route::post('tasks/{task}/update', [TaskController::class, 'update']);
            Route::resource(name: 'tasks', controller: TaskController::class);
        });
    }
}
