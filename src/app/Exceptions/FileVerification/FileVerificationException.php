<?php

declare(strict_types=1);

namespace App\Exceptions\FileVerification;

use App\Dto\FileVerification\FileVerificationResult\FileVerificationResponseDto;
use App\Services\FileVerification\FileVerificationResultService;
use Illuminate\Contracts\Support\Responsable;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class FileVerificationException extends RuntimeException implements Responsable
{
    private FileVerificationResultService $verificationResultService;

    public function __construct(
        private readonly string $issuer,
        private readonly string $result,
    )
    {
        parent::__construct();
        $this->verificationResultService = app(FileVerificationResultService::class);
    }

    public function toResponse($request): Response
    {
        $this->verificationResultService->saveVerificationResult($this->result);

        return response()->json(
            new FileVerificationResponseDto(
                issuer: $this->issuer,
                result: $this->result,
            ),
            Response::HTTP_OK);
    }
}
