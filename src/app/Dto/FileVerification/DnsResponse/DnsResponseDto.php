<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\DnsResponse;

use Spatie\LaravelData\Data;

final class DnsResponseDto extends Data
{
    public function __construct(
        /** @var DnsResponseAnswerDto[] $dnsResponseAnswers */
        public array $dnsResponseAnswers,
    ) {}

    public static function from(...$payloads): static
    {
        $answerDTOs = array_map(function ($answer) {
            return DnsResponseAnswerDto::from($answer);
        }, current($payloads)['Answer']);

        return new self($answerDTOs);
    }
}
