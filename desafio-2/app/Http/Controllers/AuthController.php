<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Log;

class AuthController extends Controller
{
    /**
     * Obtém um token JWT a través das credenciais informadas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            Log::warning('Login não autorizado para usuário '.$credentials['email']);

            return response()->json(['message' => 'Não autorizado'], 401);
        }

        Log::info('Realizando login do usuário '.$credentials['email']);

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
        Log::info('Realizando logout do usuário '.auth()->user()->email);

        auth()->logout();

        return $this->success(null, 'Logout realizado com sucesso', 200);
    }

    /**
     * Registra um novo usuário para autenticação na API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $user = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255',
                'password' => 'required|string|min:6',
            ]
        );

        $created = User::create($user);
        Log::info('Registrando novo usuário '.$created->email);

        return $this->success($created, 'Usuário cadastrado com sucesso', 201);
    }
}
