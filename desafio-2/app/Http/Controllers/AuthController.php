<?php

namespace App\Http\Controllers;

use App\Exceptions\UserLoginException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Log;

class AuthController extends Controller
{
    /**
     * Obtém um token JWT a través das credenciais informadas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            Log::warning('Login não autorizado para usuário {email}', ['email' => $credentials['email']]);

            throw new UserLoginException;
        }

        Log::info('Realizando login do usuário {email}', ['email' => $credentials['email']]);

        return $this->success(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
            'Usuário logado com sucesso',
            200,
        );
    }

    /**
     * Realiza o logout do usuário (invalidando o token JWT).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Log::info('Realizando logout do usuário {email}', ['email' => auth()->user()->email]);

        auth()->logout();

        return $this->success(null, 'Logout realizado com sucesso', 200);
    }

    /**
     * Registra um novo usuário para autenticação na API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        $user = $request->only(['name', 'email', 'password']);

        $created = User::create($user);
        Log::info('Registrando novo usuário ID {id} {email}', ['id' => $created->id, 'email' => $created->email]);

        return $this->success($created, 'Usuário cadastrado com sucesso', 201);
    }
}
