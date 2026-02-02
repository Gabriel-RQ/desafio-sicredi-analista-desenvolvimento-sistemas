<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
        return [
            'cpf' => ['string', 'required', 'min:14', 'max:14', 'unique:members', new Cpf],
            'name' => 'string|required|max:255',
            'phone' => 'nullable|string|min:9|max:19',
            'email' => 'email|required|max:255',
            'state' => 'string|required|max:255',
            'city' => 'string|required|max:255',
        ];
    }
}
