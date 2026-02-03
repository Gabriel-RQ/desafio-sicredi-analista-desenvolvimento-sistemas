<?php

namespace App\Http\Controllers;

use App\Exceptions\UserLoginException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Log;

#[Group('Autenticação')]
class AuthController extends Controller
{
    /**
     * Realiza o login do usuário
     *
     * Obtém um token JWT através das credenciais informadas.
     *
     * @unauthenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    #[Response(200, 'Autenticação bem sucedida', type: 'array{success: bool, message: string, data: array{access_token: string, token_type: string, expires_in: int}}')]
    #[Response(422, 'Erro de validação', type: 'array{success: bool, message: string, errors: array{property: string[]}}')]
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
     * Realiza o logout do usuário
     *
     * Invalida o token JWT.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Log::info('Realizando logout do usuário {email}', ['email' => auth()->user()->email]);

        auth()->logout();

        return response()->noContent();
    }

    /**
     * Registra um novo usuário para autenticação na API.
     *
     * @unauthenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    #[Response(201, 'Usuário cadastrado', type: 'array{success: bool, message: string, data: User}')]
    #[Response(422, 'Erro de validação', type: 'array{success: bool, message: string, errors: array{property: string[]}}')]
    public function register(RegisterRequest $request)
    {

        $user = $request->only(['name', 'email', 'password']);

        $created = User::create($user);
        Log::info('Registrando novo usuário ID {id} {email}', ['id' => $created->id, 'email' => $created->email]);

        return $this->success($created, 'Usuário cadastrado com sucesso', 201);
    }
}
