<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Book;

use App\Application\Command\Book\DeleteBookCommand;
use App\Application\Repository\Book\BookReadRepository;
use App\Application\Repository\Book\BookWriteRepository;
use App\Domain\Book\VO\BookId;

final readonly class DeleteBookCommandHandler
{
    public function __construct(
        private BookWriteRepository $bookWriteRepository,
        private BookReadRepository $bookReadRepository,
    ) {}

    public function __invoke(DeleteBookCommand $command): void
    {
        $book = $this->bookReadRepository->get(BookId::fromString($command->id));
        $this->bookWriteRepository->delete($book);
    }
}
