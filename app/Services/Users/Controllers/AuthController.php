<?php

namespace App\Services\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Users\Requests\RegisterRequest;
use App\Services\Users\Requests\LoginRequest;
use App\Services\Users\Managers\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authManager->register($request->validated());
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authManager->login($request->validated());
        return response()->json($result, 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authManager->logout($request->user());
        return response()->json(['message' => 'Ви успішно вийшли з системи'], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}