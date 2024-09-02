<?php

declare(strict_types=1);

namespace Tests\Unit\FileVerification;

use App\Dto\FileVerification\FileContent\FileContentDto;
use App\Exceptions\FileVerification\FileVerificationException;
use App\Services\FileVerification\VerificationResult;
use App\Validators\FileVerification\IssuerValidator;
use FakeData\FakeFileVerificationResultService;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Psr\Log\LoggerInterface;
use Spatie\LaravelData\Data;
use Tests\TestCase;

final class FileIssuerVerificationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(VerificationResult::class, FakeFileVerificationResultService::class);
    }

    public function test_valid_file_issuer_verified()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $httpClient = Mockery::mock(ClientInterface::class);

        $dnsResponseMock = [
            'Answer' => [
                ['data' => 'expected-key'],
            ],
        ];

        $httpClient->shouldReceive('get')
            ->once()
            ->andReturn(new Response(200, [], json_encode($dnsResponseMock)));

        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');
        $fileDto->data->issuer->identityProof->key = 'expected-key';

        $next = function ($fileDto) {
            $this->assertInstanceOf(Data::class, $fileDto);
        };

        $validator = new IssuerValidator($logger, $httpClient);
        $validator->validate($fileDto, $next);
    }

    function test_invalid_file_issuer_not_verified()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $httpClient = Mockery::mock(ClientInterface::class);

        $dnsResponseMock = [
            'Answer' => [
                ['data' => 'unrelated-key'],
            ],
        ];

        $httpClient->shouldReceive('get')
            ->once()
            ->andReturn(new Response(200, [], json_encode($dnsResponseMock)));

        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');

        $this->expectException(FileVerificationException::class);

        $validator = new IssuerValidator($logger, $httpClient);
        $validator->validate($fileDto, function () {});
    }

    public function test_empty_issuer_name()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $httpClient = Mockery::mock(ClientInterface::class);

        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');
        $fileDto->data->issuer->name = '';

        $this->expectException(FileVerificationException::class);

        $validator = new IssuerValidator($logger, $httpClient);
        $validator->validate($fileDto, function () {});
    }

    public function test_empty_identity_key_proof()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $httpClient = Mockery::mock(ClientInterface::class);

        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');
        $fileDto->data->issuer->identityProof->key = '';

        $this->expectException(FileVerificationException::class);

        $validator = new IssuerValidator($logger, $httpClient);
        $validator->validate($fileDto, function () {});
    }

    public function test_dns_bad_request()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')
            ->once()
            ->with('Invalid status code', Mockery::on(function ($arg) {
                return isset($arg['message']) && isset($arg['code']);
            }))
            ->andReturn();

        $httpClient = Mockery::mock(ClientInterface::class);
        $httpClient->shouldReceive('get')
            ->andThrowExceptions([new TransferException()]);

        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');

        $this->expectException(FileVerificationException::class);

        $validator = new IssuerValidator($logger, $httpClient);
        $validator->validate($fileDto, function () {});
    }
}
