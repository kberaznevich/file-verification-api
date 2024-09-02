<?php

declare(strict_types=1);

namespace App\Enums\FileVerification;

enum FileVerificationStatus: string
{
    case Verified = 'verified';
    case InvalidRecipient = 'invalid_recipient';
    case InvalidIssuer = 'invalid_issuer';
    case InvalidSignature = 'invalid_signature';

    public static function invalidStatuses(): array
    {
        return [
            self::InvalidRecipient->value,
            self::InvalidIssuer->value,
            self::InvalidSignature->value,
        ];
    }
}
