<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Application\Bus\QueryBus;
use App\Application\Query\Book\ListBooksQuery;
use App\Infrastructure\Driving\Http\Admin\v1\ApiResponder\ResourceResponder;
use Undabot\JsonApi\Definition\Model\Request\GetResourceCollectionRequestInterface;
use Undabot\SymfonyJsonApi\Http\Model\Response\ResourceCollectionResponse;

final class ListBookController
{
    public function __invoke(
        ResourceResponder $responder,
        GetResourceCollectionRequestInterface $request,
        QueryBus $queryBus,
    ): ResourceCollectionResponse {
        $pagination = $request->getPagination();

        $books = $queryBus->handleQuery(new ListBooksQuery(
            null === $pagination ? 0 : $pagination->getOffset(),
            null === $pagination ? 10 : $pagination->getSize(),
        ));

        return $responder->resourceObjectCollection($books);
    }
}
