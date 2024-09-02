<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileContent;

use Spatie\LaravelData\Data;

final class IdentityProofDto extends Data
{
    public string $type;
    public string $key;
    public string $location;
}
