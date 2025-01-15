<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exception;

abstract class EntityNotFoundException extends \Exception
{
    public function __construct(string $id)
    {
        $message = \sprintf('%s with Id %s not found', $this->getClassName(), $id);

        parent::__construct($message, 404);
    }

    abstract public function getClassName(): string;
}
