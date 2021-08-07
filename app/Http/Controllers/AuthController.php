<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function unauthorized(): string
    {
        return response()->json(['error' => 'Não Autorizado'], 401);
    }

    public function register(Request $request, AuthRepository $auth): array
    {
        return $auth->registerUser($request);
    }

    public function login(Request $request, AuthRepository $auth): array
    {
        return $auth->authenticateUser($request);
    }

    public function validateToken(Request $request, AuthRepository $auth): array
    {
        return $auth->refreshToken();
    }

    public function logout(AuthRepository $auth): array
    {
        return $auth->logout();
    }
}
