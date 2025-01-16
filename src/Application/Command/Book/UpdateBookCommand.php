<?php

declare(strict_types=1);

namespace App\Application\Command\Book;

use App\Application\Command\Command;

class UpdateBookCommand implements Command
{
    /** @param array<int, string> $authorIds */
    public function __construct(
        public string $id,
        public string $title,
        public string $isbn,
        public int $year,
        public string $genre,
        public array $authorIds,
    ) {}
}
