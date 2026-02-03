<?php

use App\DTOs\MemberRegistrationDTO;
use App\DTOs\MemberUpdateDTO;
use Illuminate\Http\Request;

it('formata cidade e estado corretamente', function () {
    // Arrange
    $request = new Request([
        'cpf' => '123',
        'name' => 'Test',
        'phone' => '123',
        'email' => 'test@test.com',
        'city' => ' porto alegre ', // minúsculo e com espaços
        'state' => ' rio grande do sul ',           // minúsculo e com espaços
    ]);

    // Act
    $dto = MemberRegistrationDTO::fromRequest($request);

    // Assert
    expect($dto->city)->toBe('Porto Alegre')
        ->and($dto->state)->toBe('Rio Grande Do Sul');
});

it('filtra valores nulos', function () {
    // Arrange
    $dto = new MemberUpdateDTO(name: 'Nome Atualizado');

    // Act
    $array = $dto->toArray();

    // Assert
    expect($array)->toHaveKey('name')
        ->and($array)->not->toHaveKey('email')
        ->and($array)->not->toHaveKey('cpf');
});
