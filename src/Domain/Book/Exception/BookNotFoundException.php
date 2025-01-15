<?php

declare(strict_types=1);

namespace App\Domain\Book\Exception;

use App\Domain\Book\Book;
use App\Domain\Shared\Exception\EntityNotFoundException;

class BookNotFoundException extends EntityNotFoundException
{
    public function getClassName(): string
    {
        return Book::class;
    }
}
