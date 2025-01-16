<?php

namespace App\Http\Requests\Api\v1;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->id() === $this->user()->id || $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|not_regex:/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/|between:2,75',
            'email' => 'sometimes|email:rfc,dns',
            'password' => 'sometimes|between:8,20',
            'role' => [
                'sometimes', Rule::in([
                    'admin',
                    'films',
                    'people',
                    'locations',
                    'species',
                    'vehicles',
                ])
            ],
        ];
    }
}
