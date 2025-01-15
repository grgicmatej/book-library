<?php

declare(strict_types=1);

namespace App\Domain\Shared\VO;

use Assert\Assertion;

final readonly class Name
{
    public function __construct(private string $name)
    {
        Assertion::string($name);
        Assertion::maxLength($name, 40);
        Assertion::minLength($name, 1);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
