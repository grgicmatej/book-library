<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exception;

abstract class TranslatableException extends \Exception implements LoggableException
{
    /** @param array<string,string> $messageParams */
    public function __construct(
        protected string $translatableMessageKey,
        protected array $messageParams,
    ) {
        parent::__construct($translatableMessageKey);
    }

    /** @return array<string,string> */
    public function getMessageParams(): array
    {
        return $this->messageParams;
    }

    public function getLoggableMessage(): string
    {
        return $this->translatableMessageKey;
    }
}
