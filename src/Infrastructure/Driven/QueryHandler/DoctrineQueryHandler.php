<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\QueryHandler;

use App\Application\Query\PaginatedQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Undabot\SymfonyJsonApi\Model\Collection\ArrayCollection;
use Undabot\SymfonyJsonApi\Model\Collection\ObjectCollection;
use Undabot\SymfonyJsonApi\Service\Pagination\Paginator;

abstract readonly class DoctrineQueryHandler implements PaginatedQueryHandler
{
    public function __construct(protected EntityManagerInterface $entityManager) {}

    public function makePaginatedResultsIfNeeded(QueryBuilder $queryBuilder, PaginatedQuery $query): ObjectCollection
    {
        if (null !== $query->offset && null !== $query->size) {
            return (new Paginator())
                ->createPaginatedCollection(
                    $queryBuilder,
                    $query->offset,
                    $query->size
                );
        }

        /** @var object[] $result */
        $result = $queryBuilder->getQuery()->getResult();

        return new ArrayCollection($result);
    }
}
