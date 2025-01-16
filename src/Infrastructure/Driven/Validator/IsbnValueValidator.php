<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Validator;

use App\Application\Validator\Isbn\IsbnValidator;
use Isbn\Isbn;

class IsbnValueValidator implements IsbnValidator
{
    public function validateIsbn(string $isbnValue): bool
    {
        $isbn = new Isbn();

        if ($isbn->validation->isbn($isbnValue)) {
            return true;
        }

        return false;
    }
}
