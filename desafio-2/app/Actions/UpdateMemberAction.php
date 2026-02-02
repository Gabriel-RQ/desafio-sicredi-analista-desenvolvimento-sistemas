<?php

namespace App\Actions;

use App\DTOs\MemberRegistrationDTO;
use App\Models\Address;
use App\Models\Member;

class UpdateMemberAction
{
    public function execute(Member $member, MemberRegistrationDTO $dto): Member
    {
        // Busca pelo endereço, caso não exista cria um novo endereço
        $address = Address::firstOrCreate([
            'city' => $dto->city,
            'state' => $dto->state,
        ]);

        $member->update(
            [
                'name' => $dto->name,
                'cpf' => $dto->cpf,
                'email' => $dto->email,
                'phone' => $dto->phone,
                'address_id' => $address->id,
            ]
        );

        return $member->refresh();
    }
}
