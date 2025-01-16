<?php

declare(strict_types=1);

namespace App\Application\Repository\Author;

use App\Domain\Author\Author;
use App\Domain\Author\VO\AuthorId;

interface AuthorReadRepository
{
    public function get(AuthorId $id): Author;
}
