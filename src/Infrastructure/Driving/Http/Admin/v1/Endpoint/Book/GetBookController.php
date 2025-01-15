<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Domain\Book\Book;
use App\Domain\Book\VO\BookId;
use App\Infrastructure\Driving\Http\Admin\v1\ApiResponder\ResourceResponder;
use App\Application\Repository\Book\BookReadRepository;
use Undabot\SymfonyJsonApi\Http\Model\Response\ResourceResponse;
use Undabot\SymfonyJsonApi\Model\Collection\UniqueCollection;

final class GetBookController
{
    public function __invoke(
        string $id,
        ResourceResponder $responder,
        BookReadRepository $bookReadRepository
    ): ResourceResponse
    {
        $book = $bookReadRepository->get(BookId::fromString($id));

        return $responder->resource($book, $this->getIncludedItems($book)->getItems());
    }

    public function getIncludedItems(Book $book): UniqueCollection
    {
        $included = new UniqueCollection();
        foreach ($book->authors() as $author) {
            $included->addObject($author);
        }

        return $included;
    }
}