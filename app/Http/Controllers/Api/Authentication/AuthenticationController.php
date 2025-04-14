<?php

namespace App\Http\Controllers\Api\Authentication;

use App\{Http\Controllers\Controller,
    Http\Requests\Api\Authentication\LoginRequest,
    Http\Requests\Api\Authentication\RegisterRequest,
    Http\Resources\UserResource,
    Models\User,
    Services\UserService,
    Utils\HttpFoundation\HttpStatus,
    Utils\HttpFoundation\Response};
use Illuminate\{Http\Request,
    Support\Facades\Auth,
    Support\Facades\Log,};

class AuthenticationController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function login(LoginRequest $request): Response
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::attempt($credentials, $request->boolean('remember_me'))) {
                return Response::error(
                    message: __('auth.failed'),
                    status: HttpStatus::HTTP_UNAUTHORIZED
                );
            }

            $user = Auth::user();

            return Response::response(
                message: __('auth.logged_successfully'),
                data: [
                    'token' => $this->userService->revokeAndCreateToken(user: $user),
                    'user' => new UserResource(resource: $user),
                ]
            );
        } catch (\Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Response::error(
                message: __('auth.login_error'),
                status: HttpStatus::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function register(RegisterRequest $request): Response
    {
        $user = $this->userService->create(data: $request->validated());

        return Response::response(
            message: __(key:'share.registered_successfully'),
            data: [
                'token' => $this->userService->revokeAndCreateToken(user: $user),
                'user' => new UserResource(resource: $user)
            ]
        );
    }

    public function logout(): Response
    {
        $user = auth()->user();
        $this->userService->revokeTokens(user: $user);
        return Response::response(
            message: __(key:'share.logout_successfully'),
            data: new UserResource($user)
        );
    }
}
