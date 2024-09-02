<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UserLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_login(): void
    {
        $password = fake()->regexify('[A-Za-z0-9]{8,12}');
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'token',
            'user' => [
                'name',
                'email',
            ],
        ]);
    }

    public function test_invalid_user_cannot_login()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('auth.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Invalid credentials']);
    }
}
