<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileVerificationResult;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Data;

final class FileVerificationResultDto extends Data implements Arrayable
{
    public function __construct(
        public int $userId,
        public string $verificationResult,
        public string $fileType = 'JSON',
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'file_type' => $this->fileType,
            'verification_result' => $this->verificationResult,
        ];
    }
}
