<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Application\Bus\CommandBus;
use App\Application\Command\Book\UpdateBookCommand;
use App\Application\Repository\Book\BookReadRepository;
use App\Domain\Book\Book;
use App\Domain\Book\VO\BookId;
use App\Infrastructure\Driven\RequestHandler\RequestHandler;
use App\Infrastructure\Driving\Http\Admin\v1\ApiResponder\ResourceResponder;
use App\Infrastructure\Driving\Http\Admin\v1\Model\Book\BookWriteModel;
use Undabot\JsonApi\Definition\Model\Request\UpdateResourceRequestInterface;
use Undabot\SymfonyJsonApi\Http\Model\Response\ResourceUpdatedResponse;
use Undabot\SymfonyJsonApi\Http\Service\SimpleResourceHandler;
use Undabot\SymfonyJsonApi\Model\Collection\UniqueCollection;

final class UpdateBookController
{
    public function __invoke(
        string $id,
        UpdateResourceRequestInterface $request,
        CommandBus $commandBus,
        RequestHandler $requestHandler,
        BookReadRepository $bookReadRepository,
        ResourceResponder $responder,
        SimpleResourceHandler $resourceHandler,
    ): ResourceUpdatedResponse {
        $book = $bookReadRepository->get(BookId::fromString($id));

        /** @var BookWriteModel $bookModel */
        $bookModel = $resourceHandler->getModelFromRequest($request, BookWriteModel::class);

        $command = new UpdateBookCommand(
            $book->id()->id,
            $bookModel->title,
            $bookModel->isbn,
            $bookModel->year,
            $bookModel->genre,
            $bookModel->authorIds
        );

        $commandBus->handleCommand($command);

        $book = $bookReadRepository->get(BookId::fromString($id));

        return $responder->resourceUpdated($book, $this->getIncludedItems($book)->getItems());
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
