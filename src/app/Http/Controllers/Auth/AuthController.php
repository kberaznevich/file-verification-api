<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Dto\Auth\AuthResponseDto;
use App\Dto\Auth\UserDto;
use App\Exceptions\Auth\InvalidUserDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    #[OA\Post(
        path: "/api/registration",
        operationId: "registerUser",
        description: "Registers a new user.",
        security: [['bearerAuth' => []]],
        requestBody:  new OA\RequestBody(
            content:  new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref:"#/components/schemas/RegisterUserRequest")
            )
        ),
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful registration",
                content: ['application/json' =>
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'token',
                                description: 'JWT token for the registered user',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'user',
                                ref: '#/components/schemas/UserDto',
                                type: 'object'
                            ),
                        ]
                    )
                ]
            ),
            new OA\Response(
                response: 422,
                description: "",
                content: ['application/json' => new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')]
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                content: ['application/json' => new OA\JsonContent(ref: '#/components/schemas/InternalServerErrorResponse')]
            )
        ]
    )]
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->authService->createUser(UserDto::from($request->validated()));
        $token = $this->authService->generateToken($user);

        return response()->json(
            new AuthResponseDto(
                $token,
                UserDto::from($user->toArray())
            ),
            Response::HTTP_OK
        );
    }

    #[OA\Post(
        path: "/api/login",
        operationId: "loginUser",
        description: "Perform user login.",
        security: [['bearerAuth' => []]],
        requestBody:  new OA\RequestBody(
            content:  new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref:"#/components/schemas/LoginUserRequest")
            )
        ),
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful login",
                content: ['application/json' =>
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'token',
                                description: 'JWT token for the logged in user',
                                type: 'string'
                            ),
                            new OA\Property(
                                property: 'user',
                                ref: '#/components/schemas/UserDto',
                                type: 'object'
                            ),
                        ]
                    )
                ]
            ),
            new OA\Response(
                response: 422,
                description: "",
                content: ['application/json' => new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')]
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error",
                content: ['application/json' => new OA\JsonContent(ref: '#/components/schemas/InternalServerErrorResponse')]
            )
        ]
    )]
    public function login(LoginUserRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            throw new InvalidUserDataException('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $this->authService->generateToken($user);

        return response()->json(
            new AuthResponseDto(
                $token,
                UserDto::from($user->toArray())
            ),
            Response::HTTP_OK
        );
    }
}
