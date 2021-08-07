<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Unit;

class AuthRepository
{
    public function registerUser($dataUser): array
    {
        $validator = Validator::make($dataUser->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        if(!$validator->fails()) {

            $newUser = new User();
            $newUser->name  = $dataUser->input('name');
            $newUser->email = $dataUser->input('email');
            $newUser->cpf   = $dataUser->input('cpf');
            $newUser->password = password_hash($dataUser->input('password'), PASSWORD_DEFAULT);
            $newUser->save();

            $token = auth()->attempt([
                'cpf' =>  $dataUser->input('cpf'),
                'password' => $dataUser->input('password')
            ]);

            if(!$token) {
                return ['error' => "Ocorreu um erro."];
            }

            $user = auth()->user();
            $properties = Unit::select(['id','nome'])->where('id_owner', $user['id'])->get();
            $user['properties'] = $properties;

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => '', 'token' => $token, 'user' => $user];
    }

    public function authenticateUser($dataUser): array
    {
        $validator = Validator::make($dataUser->all(), [
            'cpf' => 'required|digits:11',
            'password' => 'required',
        ]);

        if(!$validator->fails()) {

            $token = auth()->attempt([
                'cpf' =>  $dataUser->input('cpf'),
                'password' => $dataUser->input('password')
            ]);

            if(!$token) {
                return ['error' => "CPF e/ou Senha estão errados."];
            }

            $user = auth()->user();
            $properties = Unit::select(['id','nome'])->where('id_owner', $user['id'])->get();
            $user['properties'] = $properties;

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => '', 'token' => $token, 'user' => $user];
    }

    public function refreshToken(): array
    {
        $user = auth()->user();
        $properties = Unit::select(['id','nome'])->where('id_owner', $user['id'])->get();
        $user['properties'] = $properties;
        return ['error' => '', 'user' => $user];
    }

    public function logout(): array
    {
        auth()->logout();
        return ['error' => ''];
    }
}
