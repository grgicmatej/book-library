<?php

declare(strict_types=1);

namespace App\Domain\Author\Exception;

use App\Domain\Author\Author;
use App\Domain\Shared\Exception\EntityNotFoundException;

class AuthorNotFoundException extends EntityNotFoundException
{
    public function getClassName(): string
    {
        return Author::class;
    }
}
