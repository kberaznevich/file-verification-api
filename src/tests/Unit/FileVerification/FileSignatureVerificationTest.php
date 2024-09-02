<?php

declare(strict_types=1);

namespace Tests\Unit\FileVerification;

use App\Dto\FileVerification\FileContent\FileContentDto;
use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use App\Services\FileVerification\VerificationResult;
use App\Validators\FileVerification\SignatureValidator;
use FakeData\FakeFileVerificationResultService;
use Mockery;
use Tests\TestCase;

final class FileSignatureVerificationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(VerificationResult::class, FakeFileVerificationResultService::class);
    }

    function test_valid_file_signature_verified()
    {
        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');
        $closureCalled = false;

        $next = function () use (&$closureCalled) {
            $closureCalled = true;
        };

        $validator = Mockery::mock(SignatureValidator::class);
        $validator->shouldReceive('validate')
            ->once()
            ->with($fileDto, Mockery::type('Closure'))
            ->andReturnUsing(function ($dto, $next) {
                $next($dto);
            });
        $validator->validate($fileDto, $next);

        $this->assertTrue($closureCalled);
    }

    function test_invalid_file_signature_not_verified()
    {
        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/invalid-signature.json');
        $fileDto->signature->targetHash = '12345';

        $validator = Mockery::mock(SignatureValidator::class);
        $validator->shouldReceive('validate')
            ->once()
            ->with($fileDto, Mockery::type('Closure'))
            ->andThrow(new FileVerificationException('issuer', FileVerificationStatus::InvalidSignature->value));

        $this->expectException(FileVerificationException::class);

        $validator->validate($fileDto, function () {});
    }
}
