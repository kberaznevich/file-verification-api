<?php

declare(strict_types=1);

namespace Tests\Unit\FileVerification;

use App\Dto\FileVerification\FileContent\FileContentDto;
use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use App\Validators\FileVerification\SignatureValidator;
use Mockery;
use Tests\TestCase;

final class FileSignatureVerificationTest extends TestCase
{
    function test_valid_file_signature_verified()
    {
        $fileDto = FileContentDto::fromFile('app/test-files/valid.json');
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
        $fileDto = FileContentDto::fromFile('app/test-files/invalid.json');
        $validator = Mockery::mock(SignatureValidator::class);

        $validator->shouldReceive('validate')
            ->once()
            ->with($fileDto, Mockery::type('Closure'))
            ->andThrow(new FileVerificationException('issuer', FileVerificationStatus::InvalidIssuer->value));

        $this->expectException(FileVerificationException::class);

        $validator->validate($fileDto, function () {});
    }
}
