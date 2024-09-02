<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileContent;

use Spatie\LaravelData\Data;

final class DataDto extends Data
{
    public string $id;
    public string $name;
    public RecipientDto $recipient;
    public IssuerDto $issuer;
    public string $issued;
}
