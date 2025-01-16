<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\Model\Book;

use App\Domain\Book\Book;
use Undabot\SymfonyJsonApi\Model\ApiModel;
use Undabot\SymfonyJsonApi\Model\Resource\Annotation\Attribute;
use Undabot\SymfonyJsonApi\Service\Resource\Validation\Constraint\ResourceType;

/** @ResourceType(type="book") */
final readonly class BookWriteModel implements ApiModel
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
         * @Attribute
         *
         * @var array<int,string>
         */
        public array $authorIds,
    ) {}
}
