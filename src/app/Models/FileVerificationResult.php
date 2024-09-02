<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class FileVerificationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_type',
        'verification_result',
        'timestamp',
    ];

    public function users(): HasOne
    {
        return $this->HasOne(User::class);
    }
}
