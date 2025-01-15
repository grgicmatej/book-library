<?php

declare(strict_types=1);

namespace App\Domain\Book\VO;

final readonly class Isbn
{
    public function __construct(public string $isbn) {}

    public function isbn(): string
    {
        return $this->isbn;
    }
}
