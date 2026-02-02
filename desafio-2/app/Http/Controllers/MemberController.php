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

        return response()->json(MemberResource::collection($members), 200);
    }

    /**
     * Cria um novo associado.
     */
    public function store(StoreMemberRequest $request, CreateMemberAction $action)
    {
        $dto = MemberRegistrationDTO::fromRequest($request);

        $createdMember = $action->execute($dto);

        Log::info('Registrando novo associado '.$createdMember->name.' com email '.$createdMember->email);

        return response()->json([
            'message' => 'Associado cadastrado com sucesso',
            'data' => new MemberResource($createdMember),
        ]);
    }

    /**
     * Retorna os dados do associado especificado.
     */
    public function show(Member $member)
    {
        Log::info('Listando dados do associado ID '.$member->id.' '.$member->name.' com email '.$member->email);

        return response()->json(new MemberResource($member), 200);
    }

    /**
     * Atualiza os dados do associado especificado.
     */
    public function update(UpdateMemberRequest $request, Member $member, UpdateMemberAction $action)
    {
        $dto = MemberUpdateDTO::fromRequest($request);

        $updated = $action->execute($member, $dto);

        Log::info('Atualizando dados do associado ID '.$updated->id.' '.$updated->name.' com email '.$updated->email);

        return response()->json(['message' => 'Associado atualizado com sucesso', 'data' => new MemberResource($updated)], 200);
    }

    /**
     * Apaga o associado especificado.
     */
    public function destroy(Member $member)
    {
        Log::info('Excluindo associado de ID '.$member->id);

        $member->delete();

        return response()->noContent();
    }
}
