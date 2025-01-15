<?php

declare(strict_types=1);

namespace App\Domain\Shared\VO;

abstract class Id
{
    private const ID_LENGTH = 36;

    private function __construct(public string $id) {}

    public function __toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): static
    {
        /** @psalm-suppress UnsafeInstantiation */
        return new static($id); // @phpstan-ignore-line
    }

    public function equalTo(self $id): bool
    {
        return (string) $this === (string) $id && static::class === \get_class($id);
    }

    public static function isValid(string $value): bool
    {
        return self::ID_LENGTH === mb_strlen($value);
    }
}
