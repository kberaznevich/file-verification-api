<?php

namespace App\Services\FileVerification;

use App\Dto\FileVerification\FileVerificationResult\FileVerificationResultDto;
use App\Models\FileVerificationResult;
use Illuminate\Support\Facades\Auth;

class FileVerificationResultService implements VerificationResult
{
    public function saveVerificationResult(string $result): void
    {
        FileVerificationResult::create(
            (new FileVerificationResultDto(Auth::user()->id, $result))->toArray()
        );
    }
}
