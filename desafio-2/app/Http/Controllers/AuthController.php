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

        return $this->respondWithToken($token);
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

        return response()->json(['message' => 'Logout realizado com sucesso']);
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

        return response()->json(['message' => 'Usuário cadastrado com sucesso', 'data' => $created], 201);
    }

    /**
     * Retorna a estrutura da resposta contendo o token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
