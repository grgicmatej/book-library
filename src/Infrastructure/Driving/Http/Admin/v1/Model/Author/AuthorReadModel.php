<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Model\Author;

use App\Domain\Author\Author;
use App\Domain\Book\Book;
use Undabot\SymfonyJsonApi\Model\ApiModel;
use Undabot\SymfonyJsonApi\Model\Resource\Annotation\Attribute;
use Undabot\SymfonyJsonApi\Service\Resource\Validation\Constraint\ResourceType;
use Undabot\SymfonyJsonApi\Model\Resource\Annotation\ToMany;

/** @ResourceType(type="author") */
final readonly class AuthorReadModel implements ApiModel
{
    public function __construct(
        public string $id,
        /** @Attribute */
        public string $name,
        /**
         * @var array<int,string>
         *
         * @ToMany (name="books", type="book")
         */
        public array $bookIds,
    ) {}

    public static function fromEntity(Author $author): self
    {
        return new self(
            (string) $author->id(),
            (string) $author->name(),
            $author->books()->map(static function (Book $book): string {
                return (string) $book->id();
            })->toArray(),
        );
    }
}
