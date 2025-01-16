<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\QueryHandler\Book;

use App\Application\Query\Book\ListBooksQuery;
use App\Domain\Book\Book;
use App\Infrastructure\Driven\QueryHandler\DoctrineQueryHandler;
use Undabot\SymfonyJsonApi\Model\Collection\ObjectCollection;

final readonly class ListBookQueryHandler extends DoctrineQueryHandler
{
    public function __invoke(ListBooksQuery $query): ObjectCollection
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('b')
            ->from(Book::class, 'b');

        return $this->makePaginatedResultsIfNeeded($queryBuilder, $query);
    }
}
