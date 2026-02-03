<?php

use App\Actions\CreateMemberAction;
use App\DTOs\MemberRegistrationDTO;
use App\Exceptions\MemberCreationException;
use App\Models\Address;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('CreateMemberAction', function () {

    it('cria um novo associado e um novo endereço quando o endereço não existe', function () {
        // Arrange
        $dto = new MemberRegistrationDTO(
            cpf: '123.456.789-00',
            name: 'João Paulo',
            phone: '(55) 55 99999-9999',
            email: 'joao@email.com',
            state: 'Rio Grande do Sul',
            city: 'Porto Alegre'
        );

        // Act
        $action = new CreateMemberAction;
        $member = $action->execute($dto);

        // Assert
        expect($member)->toBeInstanceOf(Member::class)
            ->and($member->name)->toBe($dto->name)
            ->and($member->address_id)->not->toBeNull();

        $address = Address::find($member->address_id);
        expect($address->city)->toBe($dto->city)
            ->and($address->state)->toBe($dto->state);
    });

    it('usa um endereço existente se a cidade e estado existirem', function () {
        // Arrange
        $existingAddress = Address::create([
            'city' => 'Frederico Westphalen',
            'state' => 'Rio Grande do Sul',
        ]);

        $dto = new MemberRegistrationDTO(
            cpf: '111.222.333-44',
            name: 'Associado Sicredi',
            phone: '(55) 55 99999-9999',
            email: 'associado@sicredi.com.br',
            state: 'Rio Grande do Sul',
            city: 'Frederico Westphalen'
        );

        // Act
        $action = new CreateMemberAction;
        $member = $action->execute($dto);

        // Assert
        expect($member->address_id)->toBe($existingAddress->id)
            ->and(Address::count())->toBe(1);
    });

    it('lança uma exceção customizada e faz rollback da transação em caso de erro', function () {
        // Arrange
        $address = Address::create([
            'city' => 'Cidade Teste',
            'state' => 'UF',
        ]);

        Member::create([
            'name' => 'Ocupante do CPF',
            'cpf' => '123.456.789-00', // CPF que será duplicado
            'email' => 'ocupante@teste.com',
            'phone' => null,
            'address_id' => $address->id,
        ]);

        $dto = new MemberRegistrationDTO(
            cpf: '123.456.789-00', // CPF Duplicado! Vai gerar erro no Banco (QueryException)
            name: 'Novo Tentativa',
            phone: '5551999999999',
            email: 'novo@email.com',
            state: 'RS',
            city: 'Porto Alegre'
        );

        // Act
        $action = new CreateMemberAction;

        // Assert
        expect(fn () => $action->execute($dto))
            ->toThrow(MemberCreationException::class);

        expect(Member::count())->toBe(1);
    });
});
