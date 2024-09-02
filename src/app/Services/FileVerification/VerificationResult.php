<?php

namespace App\Services\FileVerification;

interface VerificationResult
{
    public function saveVerificationResult(string $result): void;
}
