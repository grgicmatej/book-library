<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\RequestHandler\Exception;

class InvalidValidationException extends \Exception
{
    public static function create(string $messages): string
    {
        return $messages;
    }
}
