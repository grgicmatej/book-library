<?php

declare(strict_types=1);

namespace App\Application\Query\Book;

use App\Application\Query\PaginatedQuery;

final readonly class ListBooksQuery extends PaginatedQuery
{
    public function __construct(
        ?int $offset,
        ?int $size,
    ) {
        parent::__construct($offset, $size);
    }
}