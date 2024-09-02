<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileContent;

use Spatie\LaravelData\Data;

final class SignatureDto extends Data
{
    public string $type;
    public string $targetHash;
}
