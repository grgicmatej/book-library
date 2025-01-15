<?php

declare(strict_types=1);

namespace App\Domain\Shared\VO;

use Assert\Assertion;

final class NullableClock
{
    public function __construct(private ?int $timestamp)
    {
        if (null !== $this->timestamp) {
            try {
                Assertion::maxLength((string) $this->timestamp, 10, 'errors.invalid_argument.clock_timestamp_must_have_max_length_10');
            } catch (\Throwable $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    public static function createEmpty(): self
    {
        return new self(null);
    }

    public static function fromClock(Clock $clock): self
    {
        return new self($clock->value());
    }

    public function value(): ?int
    {
        return $this->timestamp;
    }

    public function toClockOrNull(): ?Clock
    {
        if (null === $this->timestamp) {
            return null;
        }

        return new Clock($this->timestamp);
    }
}
