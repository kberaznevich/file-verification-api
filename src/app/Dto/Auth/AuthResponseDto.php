<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Dto;

final class AuthResponseDto extends Dto implements Arrayable
{
    public function __construct(
        private readonly string $token,
        private readonly UserDto $userDto
    ) {}

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'user' => $this->userDto->toArray(),
        ];
    }
}
