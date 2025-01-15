<?php

declare(strict_types=1);

namespace App\Domain\Shared\VO;

use Assert\Assertion;

final class Clock
{
    public function __construct(private int $timestamp)
    {
        try {
            Assertion::maxLength((string) $this->timestamp, 10, 'errors.invalid_argument.clock_timestamp_must_have_max_length_10');
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function __toString(): string
    {
        return (string) $this->timestamp;
    }

    public function value(): int
    {
        return $this->timestamp;
    }
}
