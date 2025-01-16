<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Book;

use App\Application\Command\Book\CreateBookCommand;
use App\Application\Repository\Book\BookWriteRepository;
use App\Application\Service\Author\AuthorFinder;
use App\Application\Validator\Isbn\IsbnValidator;
use App\Domain\Book\Book;
use App\Domain\Book\VO\BookId;
use App\Domain\Book\VO\Genre;
use App\Domain\Book\VO\Isbn;
use App\Domain\Book\VO\Title;
use App\Domain\Book\VO\Year;
use App\Domain\Shared\Exception\InvalidArgumentTranslatableException;
use Doctrine\Common\Collections\ArrayCollection;

final readonly class CreateBookCommandHandler
{
    public function __construct(
        private BookWriteRepository $bookWriteRepository,
        private AuthorFinder $authorFinder,
        private IsbnValidator $isbnValidator
    ) {}

    public function __invoke(CreateBookCommand $command): void
    {
        if (false === !$this->isbnValidator->validateIsbn($command->isbn)) {
            throw new InvalidArgumentTranslatableException('errors.invalid_param.isbn');
        }

        $authors = new ArrayCollection();
        foreach ($command->authorIds as $authorId) {
            $author = $this->authorFinder->findAuthor($authorId);

            if (null !== $author) {
                $authors->add($author);
            }
        }

        $book = new Book(
            BookId::fromString($command->id),
            new Isbn($command->isbn),
            new Title($command->title),
            $authors,
            new Year($command->year),
            new Genre($command->genre)
        );

        $this->bookWriteRepository->save($book);
    }
}
