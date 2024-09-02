<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(version: "1.0.0", description: "File verification api", title: "File Verification API Documentation"),
    OA\SecurityScheme(
        securityScheme: 'bearerAuth',
        type: "http",
        name: "Authorization",
        in: "header",
        bearerFormat: "JWT",
        scheme: "bearer",
    ),
]
#[OA\Schema(
    schema: "BadRequestResponse",
    type: 'object',
    example: ['code:' => 400, 'message' => 'Bad request'],
)]
#[OA\Schema(
    schema: "UnauthorizedResponse",
    type: 'object',
    example: ['code:' => 401, 'message' => 'This action is unauthorized'],
)]
#[OA\Schema(
    schema: "ForbiddenResponse",
    type: 'object',
    example: ['code:' => 403, 'message' => 'Invalid credentials'],
)]
#[OA\Schema(
    schema: "NotFoundResponse",
    type: 'object',
    example: ['code:' => 404, 'message' => 'Resource not found']
)]
#[OA\Schema(
    schema: "ValidationErrorResponse",
    type: 'object',
    example: ['code:' => 422, 'message' => 'Validation error'],
)]
#[OA\Schema(
    schema: "InternalServerErrorResponse",
    type: 'object',
    example: ['code:' => 500, 'message' => 'Server error'],
)]
abstract class Controller
{
}
