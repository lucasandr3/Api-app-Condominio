<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function unauthorized()
    {
        return response()->json(['error' => 'Não Autorizado'], 401);
    }

    public function register(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        if(!$validator->fails()) {



        } else {
            return $array['error'] = $validator->errors()->first();
        }

        return $array;
    }
}
