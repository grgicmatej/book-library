<?php

declare(strict_types=1);

namespace App\Application\Query;

abstract readonly class PaginatedQuery implements Query
{
    public function __construct(public ?int $offset, public ?int $size) {}
}
