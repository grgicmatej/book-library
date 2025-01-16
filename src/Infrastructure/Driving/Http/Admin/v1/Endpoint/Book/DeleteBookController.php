<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Application\Bus\CommandBus;
use App\Application\Command\Book\DeleteBookCommand;
use App\Application\Repository\Book\BookReadRepository;
use App\Domain\Book\VO\BookId;
use Undabot\SymfonyJsonApi\Http\Model\Response\ResourceDeletedResponse;

final class DeleteBookController
{
    public function __invoke(
        string $id,
        BookReadRepository $bookReadRepository,
        CommandBus $commandBus,
    ): ResourceDeletedResponse {
        $bookReadRepository->get(BookId::fromString($id));

        $commandBus->handleCommand(new DeleteBookCommand($id));

        return new ResourceDeletedResponse();
    }
}
