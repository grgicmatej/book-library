<?php

declare(strict_types=1);

namespace App\Application\Repository\Book;

use App\Domain\Book\Book;

interface BookWriteRepository
{
    public function save(Book $book): void;
}