<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Uid\Uuid;

abstract class TrackableFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    /** @return array<int,string> */
    abstract public static function ids(): array;

    abstract public static function toGenerate(): int;

    public static function getGroups(): array
    {
        return ['DevDummyData'];
    }

    protected function generateId(int $pointer): string
    {
        return \array_key_exists($pointer, static::ids()) ? static::ids()[$pointer] : (string) Uuid::v4();
    }
}
