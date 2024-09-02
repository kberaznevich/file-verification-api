<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileContent;

use Spatie\LaravelData\Data;

final class IssuerDto extends Data
{
    public string $name;
    public IdentityProofDto $identityProof;
}
