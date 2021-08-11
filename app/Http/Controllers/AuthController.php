<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function unauthorized(): string
    {
        return response()->json(['error' => 'Não Autorizado'], 401);
    }

    public function register(Request $request): array
    {
        return $this->auth->registerUser($request);
    }

    public function login(Request $request): array
    {
        return $this->auth->authenticateUser($request);
    }

    public function validateToken(Request $request): array
    {
        return $this->auth->refreshToken();
    }

    public function logout(): array
    {
        return $this->auth->logout();
    }
}
