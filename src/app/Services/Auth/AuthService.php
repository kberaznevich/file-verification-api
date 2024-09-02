<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

final class AuthService
{
    public function createUser(Data $userDto): User
    {
        return User::create($userDto->toArray());
    }

    public function generateToken(User $user): string
    {
        return $user->createToken(name: 'API Token', expiresAt: now()->addHours())->plainTextToken;
    }
}
