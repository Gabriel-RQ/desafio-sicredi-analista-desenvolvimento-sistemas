<?php

namespace App\Http\Controllers;

use App\Actions\CreateMemberAction;
use App\Actions\UpdateMemberAction;
use App\DTOs\MemberRegistrationDTO;
use App\DTOs\MemberUpdateDTO;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function store(Request $request, CreateMemberAction $action)
    {
        $request->validate([
            'cpf' => ['string', 'required', 'min:14', 'max:14', 'unique:members', new Cpf],
            'name' => 'string|required|max:255',
            'phone' => 'nullable|string|min:9|max:19',
            'email' => 'email|required|max:255',
            'state' => 'string|required|max:255',
            'city' => 'string|required|max:255',
        ]);

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
    public function update(Request $request, Member $member, UpdateMemberAction $action)
    {
        $request->validate([
            'cpf' => ['sometimes', 'string', 'min:14', 'max:14', Rule::unique('members')->ignore($member->id)],
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|min:9|max:19',
            'email' => 'sometimes|email|max:255',
            'state' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
        ]);

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
