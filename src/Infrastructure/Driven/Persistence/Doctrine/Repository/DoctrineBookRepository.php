<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Repository;

use App\Application\Repository\Book\BookReadRepository;
use App\Application\Repository\Book\BookWriteRepository;
use App\Domain\Book\Book;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Book\VO\BookId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Rfc4122\UuidV4;

/** @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Book> */
final class DoctrineBookRepository extends ServiceEntityRepository implements BookReadRepository, BookWriteRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws BookNotFoundException
     */
    public function get(BookId $id): Book
    {
        /** @var null|Book $book */
        $book = $this->find((string) $id);

        if (null === $book) {
            throw new BookNotFoundException((string) $id);
        }

        return $book;
    }

    public function nextId(): BookId
    {
        return BookId::fromString(UuidV4::uuid4()->toString());
    }

    public function save(Book $book): void
    {
        $this->_em->persist($book);
        $this->_em->flush();
    }
}
