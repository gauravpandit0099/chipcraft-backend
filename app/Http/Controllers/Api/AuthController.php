<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /* inject auth service dependency */
    public function __construct(private AuthService $authService)
    {
    }

    /* handle customer registration request */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());
            return ResponseHelper::success($result, 'Registration successful', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* handle user login request */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());
            if (!$result) {
                return ResponseHelper::error('Invalid email or password', 401);
            }
            return ResponseHelper::success($result, 'Login successful');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* fetch authenticated user profile */
    public function me(Request $request): JsonResponse
    {
        try {
            return ResponseHelper::success($request->user(), 'Profile fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* handle user logout request */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return ResponseHelper::success(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}