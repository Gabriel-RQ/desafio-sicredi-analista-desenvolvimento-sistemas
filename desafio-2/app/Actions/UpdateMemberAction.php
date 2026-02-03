<?php

namespace App\Actions;

use App\DTOs\MemberUpdateDTO;
use App\Exceptions\MemberUpdateException;
use App\Models\Address;
use App\Models\Member;
use Log;

class UpdateMemberAction
{
    public function execute(Member $member, MemberUpdateDTO $dto): Member
    {
        try {
            if ($dto->city && $dto->state) {
                // Busca pelo endereço, caso não exista cria um novo endereço
                $address = Address::firstOrCreate([
                    'city' => $dto->city,
                    'state' => $dto->state,
                ]);
                $member->address_id = $address->id;
            }

            $member->fill(array_filter(
                [
                    'name' => $dto->name,
                    'cpf' => $dto->cpf,
                    'email' => $dto->email,
                    'phone' => $dto->phone,
                ]
            ));

            $member->save();

            return $member->refresh();
        } catch (\Exception $e) {
            Log::error('Falha ao atualizar dados do associado: {msg}', ['msg' => $e->getMessage()]);

            throw new MemberUpdateException;
        }
    }
}
