<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Autenticação', function () {

    it('pode registrar um novo usuário', function () {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123456',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    });

    it('pode realizar login com credenciais válidas e receber um token jwt', function () {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => '123456',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@example.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                ],
            ]);
    });

    it('não pode realizar login com credenciais inválidas', function () {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => '123456',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@example.com',
            'password' => 'senha-errada',
        ]);

        $response->assertStatus(401);
    });

    it('pode realizar logout quando autenticado', function () {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/auth/logout');

        $response->assertNoContent();
    });
});
