<?php

declare(strict_types=1);

namespace App\Http\Requests\FileVerification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "VerifyFileRequest",
        required: ["file"],
        properties: [
            new OA\Property(
                property: "file",
                description: "The file to be verified.",
                type: 'file',
            ),
        ],
        type: "object"
    )
]
final class VerifyFileRequest extends FormRequest
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
            'file' => ['required', 'max:2000', 'mimes:json'],
        ];
    }
}
