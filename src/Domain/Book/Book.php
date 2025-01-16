<?php

declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Author\Author;
use App\Domain\Book\VO\BookId;
use App\Domain\Book\VO\Genre;
use App\Domain\Book\VO\Isbn;
use App\Domain\Book\VO\Title;
use App\Domain\Book\VO\Year;
use Doctrine\Common\Collections\Collection;

class Book
{
    /** @param  Collection<int, Author> $authors */
    public function __construct(
        private BookId $id,
        private Isbn $isbn,
        private Title $title,
        private Collection $authors,
        private Year $year,
        private Genre $genre,
    ) {}

    public function id(): BookId
    {
        return $this->id;
    }

    public function isbn(): Isbn
    {
        return $this->isbn;
    }

    public function title(): Title
    {
        return $this->title;
    }

    /** @return Collection<int, Author> */
    public function authors(): Collection
    {
        return $this->authors;
    }

    public function year(): Year
    {
        return $this->year;
    }

    public function genre(): Genre
    {
        return $this->genre;
    }

    /** @param  Collection<int, Author> $authors */
    public function update(
        Isbn $isbn,
        Title $title,
        Collection $authors,
        Year $year,
        Genre $genre
    ): void {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->authors = $authors;
        $this->year = $year;
        $this->genre = $genre;
    }
}
