<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_registration(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('auth.registration'), [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => $password = fake()->regexify('[A-Za-z0-9]{8,12}'),
            'password_confirmation' => $password,
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

    public function test_invalid_user_cannot_register()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('auth.registration'), [
            'name' => fake()->name(),
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}
