<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|not_regex:/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/|between:2,75',
            'email' => 'email:rfc,dns',
            'password' => 'required|between:8,20',
            'role' => [
                'required', Rule::in([
                    'admin',
                    'films',
                    'people',
                    'locations',
                    'species',
                    'vehicles',
                ])],
        ];
    }
}
