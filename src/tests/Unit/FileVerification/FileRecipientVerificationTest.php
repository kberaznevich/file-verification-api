<?php

declare(strict_types=1);

namespace Tests\Unit\FileVerification;

use App\Dto\FileVerification\FileContent\FileContentDto;
use App\Enums\FileVerification\FileVerificationStatus;
use App\Exceptions\FileVerification\FileVerificationException;
use App\Validators\FileVerification\RecipientValidator;
use Mockery;
use Tests\TestCase;

final class FileRecipientVerificationTest extends TestCase
{
    function test_valid_file_recipient_verified()
    {
        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/valid.json');
        $closureCalled = false;

        $next = function () use (&$closureCalled) {
            $closureCalled = true;
        };

        $validator = Mockery::mock(RecipientValidator::class);
        $validator->shouldReceive('validate')
            ->once()
            ->with($fileDto, Mockery::type('Closure'))
            ->andReturnUsing(function ($dto, $next) {
                $next($dto);
            });
        $validator->validate($fileDto, $next);

        $this->assertTrue($closureCalled);
    }

    function test_invalid_file_recipient_not_verified()
    {
        $fileDto = FileContentDto::fromFile('tests/FakeData/FileVerification/invalid-recipient.json');

        $validator = Mockery::mock(RecipientValidator::class);
        $validator->shouldReceive('validate')
            ->once()
            ->with($fileDto, Mockery::type('Closure'))
            ->andThrow(new FileVerificationException('issuer', FileVerificationStatus::InvalidRecipient->value));

        $this->expectException(FileVerificationException::class);

        $validator->validate($fileDto, function () {});
    }
}
