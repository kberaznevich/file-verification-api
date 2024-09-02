<?php

declare(strict_types=1);

namespace Tests\Feature\FileVerification;

use App\Enums\FileVerification\FileVerificationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class FileVerificationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = User::factory()->create();
    }

    public function test_valid_file_verified()
    {
        $filePath = storage_path('app/test-files/valid.json');
        $file = new UploadedFile($filePath, 'valid.json', 'application/json', null, true);

        $response = $this->actingAs($this->testUser)->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('files.verification'), [
            'file' => $file,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'issuer',
                'result',
            ],
        ]);

        $verificationData = $response->json('data');
        $this->assertEquals(FileVerificationStatus::Verified->value, $verificationData['result']);
    }

    public function test_invalid_file_not_verified()
    {
        $filePath = storage_path('app/test-files/invalid.json');
        $file = new UploadedFile($filePath, 'invalid.json', 'application/json', null, true);

        $response = $this->actingAs($this->testUser)->withHeaders([
            'Accept' => 'application/json'
        ])->postJson(route('files.verification'), [
            'file' => $file,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'issuer',
                'result',
            ],
        ]);

        $verificationData = $response->json('data');
        $this->assertTrue(
            in_array($verificationData['result'], FileVerificationStatus::invalidStatuses()),
        );
    }

}
