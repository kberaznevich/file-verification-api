<?php

declare(strict_types=1);

namespace App\Validators\FileVerification;

use App\Dto\FileVerification\DnsResponse\DnsResponseDto;
use App\Dto\FileVerification\FileContent\IssuerDto;
use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Spatie\LaravelData\Data;

final readonly class IssuerValidator implements Validator
{
    public function __construct(
        private LoggerInterface $logger,
        private ClientInterface $httpClient
    ) {}

    /**
     * @throws FileVerificationException
     */
    public function validate(Data $dto, \Closure $next): void
    {
        $issuer = $dto->data->issuer;

        if (empty($issuer->name) || empty($issuer->identityProof)) {
            throw new FileVerificationException($issuer->name, FileVerificationStatus::InvalidIssuer->value);
        }

        if (empty($issuer->identityProof->key) || empty($dto->data->issuer->identityProof->location)) {
            throw new FileVerificationException($issuer->name, FileVerificationStatus::InvalidIssuer->value);
        }

        $this->validateDnsRecord($issuer);

        $next($dto);
    }

    private function validateDnsRecord(IssuerDto $issuer): void
    {
        try {
            $response = $this->httpClient->get(config('system.dns_url'), [
                RequestOptions::QUERY => [
                    'name' => $issuer->identityProof->location,
                    'type' => 'TXT',
                ],
            ]);
        } catch (GuzzleException $exception) {
                $this->logger->error(
                    'Invalid status code',
                    [
                        'message' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                    ]
                );
                throw new FileVerificationException($issuer->name, FileVerificationStatus::InvalidIssuer->value);
        }

        $dnsResponseDto = DnsResponseDto::from(json_decode($response->getBody()->getContents(), true));

        foreach ($dnsResponseDto->dnsResponseAnswers as $answer) {
            if (str_contains($answer->data, $issuer->identityProof->key)) {
                return;
            }
        }

        throw new FileVerificationException($issuer->name, FileVerificationStatus::InvalidIssuer->value);
    }
}
