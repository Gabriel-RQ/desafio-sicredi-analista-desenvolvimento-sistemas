<?php

namespace App\Actions;

use App\DTOs\MemberRegistrationDTO;
use App\Models\Address;
use App\Models\Member;

class CreateMemberAction
{
    public function execute(MemberRegistrationDTO $dto): Member
    {
        // Busca pelo endereço, caso não exista cria um novo endereço
        $address = Address::firstOrCreate([
            'city' => $dto->city,
            'state' => $dto->state,
        ]);

        return Member::create([
            'name' => $dto->name,
            'cpf' => $dto->cpf,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'address_id' => $address->id,
        ]);
    }
}
