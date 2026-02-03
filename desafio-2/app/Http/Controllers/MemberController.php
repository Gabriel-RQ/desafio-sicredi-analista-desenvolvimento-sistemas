<?php

namespace App\Http\Controllers;

use App\Actions\CreateMemberAction;
use App\Actions\UpdateMemberAction;
use App\DTOs\MemberRegistrationDTO;
use App\DTOs\MemberUpdateDTO;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Log;

class MemberController extends Controller
{
    /**
     * Retorna uma listagem de todos os associados.
     */
    public function index()
    {
        Log::info('Listando todos os associados');

        $members = Member::all();

        return $this->success(MemberResource::collection($members), 'Listagem de associados', 200);
    }

    /**
     * Cria um novo associado.
     */
    public function store(StoreMemberRequest $request, CreateMemberAction $action)
    {
        $dto = MemberRegistrationDTO::fromRequest($request);

        $createdMember = $action->execute($dto);

        Log::info('Registrando novo associado {name} com email {email}', ['name' => $createdMember->name, 'email' => $createdMember->email]);

        return $this->success(new MemberResource($createdMember), 'Associado cadastrado com sucesso', 201);
    }

    /**
     * Retorna os dados do associado especificado.
     */
    public function show(Member $member)
    {
        Log::info('Listando dados do associado ID {id}', ['id' => $member->id]);

        return $this->success(new MemberResource($member), 'Dados do associado', 200);
    }

    /**
     * Atualiza os dados do associado especificado.
     */
    public function update(UpdateMemberRequest $request, Member $member, UpdateMemberAction $action)
    {
        $dto = MemberUpdateDTO::fromRequest($request);

        $updated = $action->execute($member, $dto);

        Log::info('Atualizando dados do associado ID {id}', ['id' => $updated->id]);

        return $this->success(new MemberResource($updated), 'Associado atualizado com sucesso', 200);
    }

    /**
     * Apaga o associado especificado.
     */
    public function destroy(Member $member)
    {
        Log::info('Excluindo associado de ID {id}', ['id' => $member->id]);

        $member->delete();

        return response()->noContent();
    }
}
