<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\DatabaseIdentifier\Doctrine;

use App\Domain\Shared\VO\Id;
use Assert\Assertion;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

abstract class IdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        if (empty($value)) {
            return null;
        }

        $idClass = $this->getIdClass();
        if ($value instanceof $idClass && $value instanceof Id) {
            return $value;
        }

        try {
            Assertion::string($value);
            $id = $this->createIdFromString($value);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $id;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }

        $idClass = $this->getIdClass();
        if ($value instanceof $idClass && method_exists($value, '__toString')) {
            return (string) $value;
        }

        if (\is_string($value) && $this->isValid($value)) {
            return $value;
        }

        if (\is_object($value) && method_exists($value, '__toString') && $this->isValid((string) $value)) {
            return (string) $value;
        }

        throw ConversionException::conversionFailed($value, $this->getName());
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    abstract protected function getIdClass(): string;

    abstract protected function createIdFromString(string $value): Id;

    abstract protected function isValid(string $value): bool;
}
