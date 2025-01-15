<?php

declare(strict_types=1);

namespace App\Domain\Book\VO;

final readonly class Title
{
    public function __construct(public string $title) {}

    public function isbn(): string
    {
        return $this->title;
    }
}
