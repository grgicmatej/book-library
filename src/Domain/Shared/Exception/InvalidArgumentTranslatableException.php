<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exception;

final class InvalidArgumentTranslatableException extends TranslatableException
{
    public function __construct(string $messageKey)
    {
        parent::__construct($messageKey, []);
    }
}
