<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Book;

use App\Application\Command\Book\UpdateBookCommand;
use App\Application\Repository\Book\BookReadRepository;
use App\Application\Repository\Book\BookWriteRepository;
use App\Application\Service\Author\AuthorFinder;
use App\Application\Validator\Isbn\IsbnValidator;
use App\Domain\Book\VO\BookId;
use App\Domain\Book\VO\Genre;
use App\Domain\Book\VO\Isbn;
use App\Domain\Book\VO\Title;
use App\Domain\Book\VO\Year;
use App\Domain\Shared\Exception\InvalidArgumentTranslatableException;
use Doctrine\Common\Collections\ArrayCollection;

final readonly class UpdateBookCommandHandler
{
    public function __construct(
        private BookWriteRepository $bookWriteRepository,
        private BookReadRepository $bookReadRepository,
        private AuthorFinder $authorFinder,
        private IsbnValidator $isbnValidator
    ) {}

    public function __invoke(UpdateBookCommand $command): void
    {
        if (false === $this->isbnValidator->validateIsbn($command->isbn)) {
            throw new InvalidArgumentTranslatableException('errors.invalid_param.isbn');
        }

        $authors = new ArrayCollection();
        foreach ($command->authorIds as $authorId) {
            $author = $this->authorFinder->findAuthor($authorId);

            if (null !== $author) {
                $authors->add($author);
            }
        }

        $book = $this->bookReadRepository->get(BookId::fromString($command->id));

        $book->update(
            new Isbn($command->isbn),
            new Title($command->title),
            $authors,
            new Year($command->year),
            new Genre($command->genre)
        );

        $this->bookWriteRepository->save($book);
    }
}
