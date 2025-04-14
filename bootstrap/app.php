<?php

use Illuminate\{Foundation\Application,
    Foundation\Configuration\Exceptions,
    Foundation\Configuration\Middleware,
    Auth\AuthenticationException,
    Database\Eloquent\ModelNotFoundException,
    Validation\ValidationException,
    Support\Facades\Route,
    Support\Facades\Request};
use App\Http\Middleware\Localization;
use App\Http\Middleware\EnsureSessionId;
use App\Http\Middleware\ApiKeyMiddleware;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
        },
    )
    ->withProviders([
        Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localize'                => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'    => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect'   => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'    => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'          => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]);
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            Localization::class,
            EnsureSessionId::class
        ]);
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);
        $middleware->prepend(Localization::class);
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'social/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if (Request::is('api/*')) {
            $exceptions->renderable(function (NotFoundHttpException $e) {
                return response()->json([
                    'status' => 404,
                    'message' => $e->getMessage()
                ], 404);
            });
            $exceptions->renderable(function (ModelNotFoundException $e) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Invalid Data Or Not Found (isModelNotFoundException)'
                ], 404);
            });
            $exceptions->renderable(function (ValidationException $e) {
                $errors = $e->errors();
                return response()->json([
                    'status' => 422,
                    'message' => $e->getMessage(),
                    'errors' => $errors
                ], 422);
            });
            $exceptions->renderable(function (AuthenticationException $e) {
                return response()->json([
                    'status' => 401,
                    'message' => $e->getMessage()
                ], 401);
            });
            $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
                return response()->json([
                    'status' => 405,
                    'message' => $e->getMessage()
                ], 405);
            });
            $exceptions->renderable(function (BadRequestException $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            });
            $exceptions->renderable(function (Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            });
        }
    })->create();
