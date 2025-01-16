<?php

declare(strict_types=1);

namespace App\Application\Validator\Isbn;

interface IsbnValidator
{
    public function validateIsbn(string $isbnValue): bool;
}
