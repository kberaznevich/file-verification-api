<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use Illuminate\Contracts\Support\Responsable;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidUserDataException extends RuntimeException implements Responsable
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public function toResponse($request): Response
    {
        return response()->json([
            'message' => $this->message
        ], $this->code);
    }
}
