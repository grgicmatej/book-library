<?php

declare(strict_types=1);

namespace App\Application\Repository\Book;

use App\Domain\Book\Book;
use App\Domain\Book\VO\BookId;

interface BookReadRepository
{
    public function get(BookId $id): Book;

    public function nextId(): BookId;
}
