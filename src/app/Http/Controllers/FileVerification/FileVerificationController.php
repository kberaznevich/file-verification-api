<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileVerification;

use App\Dto\FileVerification\FileContent\FileContentDto;
use App\Dto\FileVerification\FileVerificationResult\FileVerificationResponseDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileVerification\VerifyFileRequest;
use App\Services\FileVerification\FileVerificationResultService;
use App\Services\FileVerification\FileVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

final class FileVerificationController extends Controller
{
    public function __construct(
        private readonly FileVerificationService $verificationService,
        private readonly FileVerificationResultService $verificationResultService,
    )
    {
    }

    #[OA\Post(
        path: "/api/files/verification",
        operationId: "fileVerification",
        description: "File verification.",
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: "#/components/schemas/VerifyFileRequest")
            )
        ),
        tags: ["File Verification"],
        responses: [
            new OA\Response(
                response: 200,
                description: "File verification finished",
                content: ['application/json' =>
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'user',
                                ref: '#/components/schemas/FileVerificationResponseDto',
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
    public function __invoke(VerifyFileRequest $request): JsonResponse
    {
        $fileContent = File::get($request->file('file')->getRealPath());
        $dto = FileContentDto::from(json_decode($fileContent, true));
        $result = $this->verificationService->verify($dto);
        $this->verificationResultService->saveVerificationResult($result);

        return response()->json(
            new FileVerificationResponseDto(
                issuer: $dto->data->issuer->name,
                result: $result,
            ),
            Response::HTTP_OK
        );
    }
}
