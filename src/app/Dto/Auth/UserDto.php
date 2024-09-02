<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "UserDto",
        required: ["name", "email", "password"],
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
        ],
        type: "object"
    )
]
final class UserDto extends Data
{
    public string $name;
    public string $email;
    public string $password;
}
