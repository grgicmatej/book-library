<?php

declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\Author\VO\AuthorId;
use App\Domain\Book\Book;
use App\Domain\Shared\VO\Name;
use Doctrine\Common\Collections\Collection;

class Author
{
    /** @param  Collection<int, Book> $books */
    public function __construct(
        private AuthorId $id,
        private Name $name,
        private Collection $books,
    ) {}

    public function id(): AuthorId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    /** @return Collection<int, Book> */
    public function books(): Collection
    {
        return $this->books;
    }
}
