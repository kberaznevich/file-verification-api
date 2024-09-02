<?php

namespace FakeData;

use App\Services\FileVerification\VerificationResult;

class FakeFileVerificationResultService implements VerificationResult
{

    public function saveVerificationResult(string $result): void
    {
        //
    }
}
