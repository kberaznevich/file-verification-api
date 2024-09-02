<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "LoginUserRequest",
        required: ["email", "password"],
        properties: [
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
        ],
        type: "object"
    )
]
final class LoginUserRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
