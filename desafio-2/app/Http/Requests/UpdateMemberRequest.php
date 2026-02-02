<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $member = $this->route('member');

        return [
            'cpf' => ['sometimes', 'string', 'min:14', 'max:14', Rule::unique('members')->ignore($member), new Cpf],
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|min:9|max:19',
            'email' => 'sometimes|email|max:255',
            'state' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
        ];
    }
}
