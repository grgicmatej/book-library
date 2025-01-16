<?php

declare(strict_types=1);

namespace App\Application\Command\Book;

use App\Application\Command\Command;

class DeleteBookCommand implements Command
{
    public function __construct(
        public string $id,
    ) {}
}
