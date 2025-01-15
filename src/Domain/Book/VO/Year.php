<?php

declare(strict_types=1);

namespace App\Domain\Book\VO;

class Year
{
    public function __construct(private int $year) {}

    public function year(): int
    {
        return $this->year;
    }
}
