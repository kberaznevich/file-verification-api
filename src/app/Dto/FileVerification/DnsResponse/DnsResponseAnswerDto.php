<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\DnsResponse;

use Spatie\LaravelData\Data;

final class DnsResponseAnswerDto extends Data
{
    public string $name;
    public int $type;
    public int $TTL;
    public string $data;
}
