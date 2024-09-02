<?php

namespace App\Validators\FileVerification;

use Spatie\LaravelData\Data;

interface Validator
{
    public function validate(Data $dto, \Closure $next): void;
}
