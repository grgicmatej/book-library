<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared\VO;

use App\Domain\Shared\VO\Name;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Name::class)]
class NameTest extends TestCase
{
    #[DataProvider('provideConstructWillCreateNewInstanceGivenNameIsValid')]
    public function testConstructWillCreateNewInstanceGivenNameIsValid(string $validName): void
    {
        $name = new Name($validName);

        static::assertInstanceOf(Name::class, $name);
        static::assertSame($validName, (string) $name);
    }

    #[DataProvider('provideConstructWillThrowExceptionGivenNameIsInvalidCases')]
    public function testConstructWillThrowExceptionGivenNameIsInvalid(string $invalidName): void
    {
        $this->expectException(\Exception::class);
        new Name($invalidName);
    }

    /** @return iterable<array<int, string>> */
    public static function provideConstructWillCreateNewInstanceGivenNameIsValid(): iterable
    {
        yield ['Name'];
    }

    /** @return iterable<array<int, string>> */
    public static function provideConstructWillThrowExceptionGivenNameIsInvalidCases(): iterable
    {
        yield ['Name_Name_Name_Name_Name_Name_Name_Name_Name_Name_Name_Name'];
    }
}
