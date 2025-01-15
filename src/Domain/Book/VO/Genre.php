<?php

declare(strict_types=1);

namespace App\Domain\Book\VO;

final readonly class Genre
{
    public function __construct(public string $genre) {}

    public function genre(): string
    {
        return $this->genre;
    }
}
