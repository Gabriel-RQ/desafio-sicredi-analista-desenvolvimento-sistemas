<?php

use App\Actions\UpdateMemberAction;
use App\DTOs\MemberUpdateDTO;
use App\Models\Address;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

describe('UpdateMemberAction', function () {
    it('atualiza as informações do associado corretamente', function () {
        // Arrange
        $address = Address::create(['city' => 'Frederico Westphalen', 'state' => 'Rio Grande do Sul']);
        $member = Member::create([
            'name' => 'Nome Anterior',
            'cpf' => '000.000.000-00',
            'email' => 'anterior@email.com',
            'address_id' => $address->id,
        ]);

        $dto = new MemberUpdateDTO(name: 'Nome Novo');

        // Act
        $action = new UpdateMemberAction;
        $updatedMember = $action->execute($member, $dto);

        // Assert
        expect($updatedMember->name)->toBe($dto->name)
            ->and($updatedMember->email)->toBe($member->email);
    });

    it('cria um novo endereço quando a cidade ou estado mudam', function () {
        // Arrange
        $address = Address::create(['city' => 'Frederico Westphalen', 'state' => 'Rio Grande do Sul']);
        $member = Member::create([
            'name' => 'Associado',
            'cpf' => '000.000.000-00',
            'email' => 'associado@email.com',
            'address_id' => $address->id,
        ]);

        $dto = new MemberUpdateDTO(city: 'Seberi', state: 'Rio Grande do Sul');

        // Act
        $action = new UpdateMemberAction;
        $updatedMember = $action->execute($member, $dto);

        // Assert
        expect($updatedMember->address_id)->not->toBe($address->id);

        $newAddress = Address::find($updatedMember->address_id);
        expect($newAddress->city)->toBe($dto->city)
            ->and($newAddress->state)->toBe($dto->state);
    });
});
