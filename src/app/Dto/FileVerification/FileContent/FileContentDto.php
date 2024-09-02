<?php

declare(strict_types=1);

namespace App\Dto\FileVerification\FileContent;

use Spatie\LaravelData\Data;

final class FileContentDto extends Data
{
    public DataDto $data;
    public SignatureDto $signature;

    public static function fromFile(string $path): FileContentDto
    {
        $jsonData = file_get_contents(storage_path($path));
        $dataArray = json_decode($jsonData, true);

        return FileContentDto::from($dataArray);
    }
}
