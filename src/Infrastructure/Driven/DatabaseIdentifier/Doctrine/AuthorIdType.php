<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\DatabaseIdentifier\Doctrine;

use App\Domain\Author\VO\AuthorId;
use App\Domain\Shared\VO\Id;

final class AuthorIdType extends IdType
{
    public function getName(): string
    {
        return 'authorId';
    }

    protected function getIdClass(): string
    {
        return AuthorId::class;
    }

    protected function createIdFromString(string $value): Id
    {
        return AuthorId::fromString($value);
    }

    protected function isValid(string $value): bool
    {
        return AuthorId::isValid($value);
    }
}
