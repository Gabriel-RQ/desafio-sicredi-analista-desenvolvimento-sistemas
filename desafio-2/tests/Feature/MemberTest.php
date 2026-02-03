<?php

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Associados', function () {

    it('pode listar todos os associados', function () {
        Member::factory()->count(3)->create();

        $response = authenticate()->getJson('/api/members');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'cpf', 'email', 'address'],
                ],
            ]);
    });

    it('pode cadastrar um novo associado', function () {
        $fakeMember = Member::factory()->make();

        $data = [
            'name' => 'Novo Associado',
            'cpf' => $fakeMember->cpf,
            'email' => 'novo@associado.com',
            'phone' => '(55) 55 99999-9999',
            'city' => 'Porto Alegre',
            'state' => 'Rio Grande do Sul',
        ];

        $response = authenticate()->postJson('/api/members', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Novo Associado')
            ->assertJsonPath('data.address.city', 'Porto Alegre')
            ->assertJsonPath('data.email', 'novo@associado.com');

        $this->assertDatabaseHas('members', ['cpf' => $fakeMember->cpf]);
        $this->assertDatabaseHas('addresses', ['city' => 'Porto Alegre']);
    });

    it('valida cpf duplicado ao cadastrar associado', function () {
        $existingMember = Member::factory()->create();

        $response = authenticate()->postJson('/api/members', [
            'name' => 'Duplicado',
            'cpf' => $existingMember->cpf,
            'email' => 'dup@test.com',
            'city' => 'Cidade',
            'state' => 'Estado',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    });

    it('pode consultar um associado específico', function () {
        $member = Member::factory()->create();

        $response = authenticate()->getJson("/api/members/{$member->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $member->id);
    });

    it('pode atualizar o cadastro de um associado', function () {
        $member = Member::factory()->create();

        $response = authenticate()->putJson("/api/members/{$member->id}", [
            'name' => 'Nome Atualizado',
            'city' => 'Nova Cidade',
            'state' => 'Novo Estado',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Nome Atualizado')
            ->assertJsonPath('data.address.city', 'Nova Cidade')
            ->assertJsonPath('data.address.state', 'Novo Estado');

        $this->assertDatabaseHas('members', ['id' => $member->id, 'name' => 'Nome Atualizado']);
        $this->assertDatabaseHas('addresses', ['city' => 'Nova Cidade', 'state' => 'Novo Estado']);
    });

    it('pode apagar o cadastro de um associado', function () {
        $member = Member::factory()->create();

        $response = authenticate()->deleteJson("/api/members/{$member->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    });
});
