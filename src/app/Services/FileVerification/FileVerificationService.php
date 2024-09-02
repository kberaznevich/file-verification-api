<?php

declare(strict_types=1);

namespace App\Services\FileVerification;

use App\Dto\FileVerification\FileVerificationResult\FileVerificationResultDto;
use App\Enums\FileVerification\FileVerificationStatus;
use App\Models\FileVerificationResult;
use App\Validators\FileVerification\IssuerValidator;
use App\Validators\FileVerification\RecipientValidator;
use App\Validators\FileVerification\SignatureValidator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Data;

final readonly class FileVerificationService
{
    public function __construct(
        private Pipeline $pipeline,
    ) {}

    public function verify(Data $dto): string
    {
        $this->pipeline
            ->send($dto)
            ->through(
            [
                RecipientValidator::class,
                IssuerValidator::class,
                SignatureValidator::class,
            ])
            ->via('validate')
            ->thenReturn();

        return FileVerificationStatus::Verified->value;
    }
}
