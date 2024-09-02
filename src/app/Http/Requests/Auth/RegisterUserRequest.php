<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "RegisterUserRequest",
        required: ["name", "email", "password", "password_confirmation"],
        properties: [
            new OA\Property(
                property: "name",
                description: "The name of the user.",
                type: 'string',
            ),
            new OA\Property(
                property: "email",
                description: "The email of the user.",
                type: 'string',
            ),
            new OA\Property(
                property: "password",
                description: "The password of the user.",
                type: 'string',
            ),
            new OA\Property(
                property: "password_confirmation",
                description: "The password confirmation.",
                type: 'string',
            ),
        ],
        type: "object"
    )
]
final class RegisterUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'alpha_num:ascii'],
        ];
    }
}
