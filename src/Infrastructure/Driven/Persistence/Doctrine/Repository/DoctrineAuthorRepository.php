<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Repository;

use App\Application\Repository\Author\AuthorReadRepository;
use App\Domain\Author\Author;
use App\Domain\Author\VO\AuthorId;
use App\Domain\Book\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Author> */
final class DoctrineAuthorRepository extends ServiceEntityRepository implements AuthorReadRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /** @throws BookNotFoundException */
    public function get(AuthorId $id): Author
    {
        /** @var null|Author $author */
        $author = $this->find((string) $id);

        if (null === $author) {
            throw new BookNotFoundException((string) $id);
        }

        return $author;
    }
}
