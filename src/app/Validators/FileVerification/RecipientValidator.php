<?php

declare(strict_types=1);

namespace App\Validators\FileVerification;

use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use Spatie\LaravelData\Data;

final class RecipientValidator implements Validator
{
    public function validate(Data $dto, \Closure $next): void
    {
        $recipient = $dto->data->recipient;

        if (empty($recipient->name) || empty($recipient->email)) {
            throw new FileVerificationException($dto->data->issuer->name, FileVerificationStatus::InvalidRecipient->value);
        }

        $next($dto);
    }
}
