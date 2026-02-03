<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class MemberUpdateDTO
{
    public function __construct(
        public ?string $cpf = null,
        public ?string $name = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $state = null,
        public ?string $city = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            cpf: $request->cpf,
            name: $request->name,
            phone: $request->phone,
            email: $request->email,
            state: $request->state ? ucwords(strtolower(trim($request->state))) : null,
            city: $request->city ? ucwords(strtolower(trim($request->city))) : null,
        );
    }

    public function toArray(): array
    {
        // Remove apenas o que for nulo, mantendo campos que vieram vazios se necessário
        return array_filter(get_object_vars($this), fn ($value) => ! is_null($value));
    }
}
