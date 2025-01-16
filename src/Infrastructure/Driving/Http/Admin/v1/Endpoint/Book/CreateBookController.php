<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Application\Bus\CommandBus;
use App\Application\Command\Book\CreateBookCommand;
use App\Application\Repository\Book\BookReadRepository;
use App\Domain\Book\Book;
use App\Infrastructure\Driven\RequestHandler\RequestHandler;
use App\Infrastructure\Driving\Http\Admin\v1\ApiResponder\ResourceResponder;
use App\Infrastructure\Driving\Http\Admin\v1\Model\Book\BookWriteModel;
use Undabot\JsonApi\Definition\Model\Request\CreateResourceRequestInterface;
use Undabot\SymfonyJsonApi\Http\Model\Response\ResourceCreatedResponse;
use Undabot\SymfonyJsonApi\Http\Service\SimpleResourceHandler;
use Undabot\SymfonyJsonApi\Model\Collection\UniqueCollection;

final class CreateBookController
{
    public function __invoke(
        CreateResourceRequestInterface $request,
        CommandBus $commandBus,
        RequestHandler $requestHandler,
        BookReadRepository $bookReadRepository,
        ResourceResponder $responder,
        SimpleResourceHandler $resourceHandler
    ): ResourceCreatedResponse {
        /** @var BookWriteModel $bookModel */
        $bookModel = $resourceHandler->getModelFromRequest($request, BookWriteModel::class);

        $nextId = $bookReadRepository->nextId();

        $command = new CreateBookCommand(
            $nextId->id,
            $bookModel->title,
            $bookModel->isbn,
            $bookModel->year,
            $bookModel->genre,
            $bookModel->authorIds
        );

        $commandBus->handleCommand($command);

        $book = $bookReadRepository->get($nextId);

        return $responder->resourceCreated($book, $this->getIncludedItems($book)->getItems());
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
