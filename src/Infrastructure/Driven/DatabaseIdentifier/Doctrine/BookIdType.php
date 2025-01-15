<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\DatabaseIdentifier\Doctrine;

use App\Domain\Book\VO\BookId;
use App\Domain\Shared\VO\Id;

final class BookIdType extends IdType
{
    public function getName(): string
    {
        return 'bookId';
    }

    protected function getIdClass(): string
    {
        return BookId::class;
    }

    protected function createIdFromString(string $value): Id
    {
        return BookId::fromString($value);
    }

    protected function isValid(string $value): bool
    {
        return BookId::isValid($value);
    }
}
