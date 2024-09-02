<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileVerificationResult;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Dto;

use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "FileVerificationResponseDto",
        required: ["issuer", "result"],
        properties: [
            new OA\Property(
                property: "issuer",
                description: "The issuer of the file.",
                type: 'string',
            ),
            new OA\Property(
                property: "result",
                description: "The result of the file verification.",
                type: 'string',
            ),
        ],
        type: "object"
    )
]
final class FileVerificationResponseDto extends Dto implements Arrayable
{
    public function __construct(
        public readonly string $issuer,
        public readonly string $result,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => [
                'issuer' => $this->issuer,
                'result' => $this->result,
            ]
        ];
    }
}
