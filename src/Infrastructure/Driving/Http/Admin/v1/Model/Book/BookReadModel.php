<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Model\Book;

use App\Domain\Author\Author;
use App\Domain\Book\Book;
use Undabot\SymfonyJsonApi\Model\ApiModel;
use Undabot\SymfonyJsonApi\Model\Resource\Annotation\Attribute;
use Undabot\SymfonyJsonApi\Service\Resource\Validation\Constraint\ResourceType;
use Undabot\SymfonyJsonApi\Model\Resource\Annotation\ToMany;

/** @ResourceType(type="book") */
final readonly class BookReadModel implements ApiModel
{
    public function __construct(
        public string $id,
        /** @Attribute */
        public string $isbn,
        /** @Attribute */
        public string $title,
        /** @Attribute */
        public int $year,
        /** @Attribute */
        public string $genre,
        /**
         * @var array<int,string>
         *
         * @ToMany (name="authors", type="author")
         */
        public array $authorIds,
    ) {}

    public static function fromEntity(Book $book): self
    {
        return new self(
            (string) $book->id(),
            $book->isbn()->isbn,
            $book->title()->title,
            $book->year()->year(),
            $book->genre()->genre,
            $book->authors()->map(static function (Author $author): string {
                return (string) $author->id();
            })->toArray(),
        );
    }
}
