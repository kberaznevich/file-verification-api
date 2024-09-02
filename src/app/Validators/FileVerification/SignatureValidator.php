<?php

declare(strict_types=1);

namespace App\Validators\FileVerification;

use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

final class SignatureValidator implements Validator
{
    public function validate(Data $dto, \Closure $next): void
    {
        $computedHashes = $this->computeHashes(Arr::dot($dto->data->toArray()));
        $computedTargetHash = $this->computeTargetHash($computedHashes);

        if (!hash_equals($dto->signature->targetHash, $computedTargetHash)) {
            throw new FileVerificationException($dto->data->issuer->name, FileVerificationStatus::InvalidSignature->value);
        }

        $next($dto);
    }

    private function computeHashes(array $dataArray): array
    {
        $hashes = [];

        foreach ($dataArray as $path => $value) {
            $pair = json_encode([$path => $value], JSON_UNESCAPED_SLASHES);
            $hashes[] = hash('sha256', $pair);
        }

        return $hashes;
    }

    private function computeTargetHash(array $hashes): string
    {
        sort($hashes);

        return hash('sha256', json_encode($hashes));
    }
}
