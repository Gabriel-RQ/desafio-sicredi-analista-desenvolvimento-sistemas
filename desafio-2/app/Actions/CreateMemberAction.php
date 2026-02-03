<?php

namespace App\Actions;

use App\DTOs\MemberRegistrationDTO;
use App\Exceptions\MemberCreationException;
use App\Models\Address;
use App\Models\Member;
use DB;
use Log;

class CreateMemberAction
{
    public function execute(MemberRegistrationDTO $dto): Member
    {
        return DB::transaction(function () use ($dto) {
            try {
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
            } catch (\Exception $e) {
                Log::error('Falha ao criar associado: {msg}', ['msg' => $e->getMessage()]);

                throw new MemberCreationException;
            }
        });
    }
}
