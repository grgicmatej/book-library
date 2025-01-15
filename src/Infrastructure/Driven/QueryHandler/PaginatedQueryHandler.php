<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\QueryHandler;

use App\Application\Query\PaginatedQuery;
use Doctrine\ORM\QueryBuilder;
use Undabot\SymfonyJsonApi\Model\Collection\ObjectCollection;

interface PaginatedQueryHandler
{
    public function makePaginatedResultsIfNeeded(QueryBuilder $queryBuilder, PaginatedQuery $query): ObjectCollection;
}
