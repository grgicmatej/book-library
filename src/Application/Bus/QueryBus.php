<?php

declare(strict_types=1);

namespace App\Application\Bus;

use App\Application\Query\Query;
use Undabot\SymfonyJsonApi\Model\Collection\ObjectCollection;

interface QueryBus
{
    public function handleQuery(Query $query): ObjectCollection;
}
