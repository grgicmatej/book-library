<?php

declare(strict_types=1);

namespace App\Application\Service\Author;

use App\Application\Repository\Author\AuthorReadRepository;
use App\Domain\Author\Author;
use App\Domain\Author\VO\AuthorId;

class AuthorFinder
{
    public function __construct(private AuthorReadRepository $authorReadRepository) {}

    public function findAuthor(string $id): ?Author
    {
        try {
            $author = $this->authorReadRepository->get(AuthorId::fromString($id));
        } catch (\Exception $e) {
            return null;
        }

        return $author;
    }
}
