<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Admin\v1\ApiResponder;

use App\Domain\Book\Book;
use App\Infrastructure\Driving\Http\Admin\v1\Model\Book\BookReadModel;
use Undabot\SymfonyJsonApi\Http\Service\Responder\AbstractResponder;

final class ResourceResponder extends AbstractResponder
{
    /** {@inheritdoc} */
    protected function getMap(): array
    {
        return [
            Book::class => [BookReadModel::class, 'fromEntity'],
        ];
    }
}
