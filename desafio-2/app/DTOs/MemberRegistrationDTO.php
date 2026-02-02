<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class MemberRegistrationDTO
{
    public function __construct(
        public string $cpf,
        public string $name,
        public ?string $phone,
        public string $email,
        public string $state,
        public string $city,
    ) {}

    /**
     * Obtém nova instância a partir de uma requisição.
     */
    public static function fromRequest(Request $request): MemberRegistrationDTO
    {
        return new self(
            $request->cpf,
            $request->name,
            $request->phone,
            $request->email,
            ucfirst(strtolower(trim($request->state))),
            ucfirst(strtolower(trim($request->city))),
        );
    }
}
