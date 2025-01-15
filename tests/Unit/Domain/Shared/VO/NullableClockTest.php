<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared\VO;

use App\Domain\Shared\VO\NullableClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullableClock::class)]
class NullableClockTest extends TestCase
{
    #[DataProvider('provideConstructWillCreateNewInstanceGivenTimestampIsValid')]
    public function testConstructWillCreateNewInstanceGivenTimestampIsValid(int $validTimestamp): void
    {
        $timestamp = new NullableClock($validTimestamp);

        static::assertInstanceOf(NullableClock::class, $timestamp);
        static::assertSame($validTimestamp, $timestamp->value());
    }

    #[DataProvider('provideConstructWillThrowExceptionGivenTimestampIsInvalidCases')]
    public function testConstructWillThrowExceptionGivenTimestampIsInvalid(int $invalidTimestamp): void
    {
        $this->expectException(\Exception::class);
        new NullableClock($invalidTimestamp);
    }

    public function testConstructWillCreateNewInstanceGivenTimestampIsNull(): void
    {
        $timestamp = new NullableClock(null);

        static::assertInstanceOf(NullableClock::class, $timestamp);
        static::assertNull($timestamp->value());
    }

    /** @return iterable<array<int, int>> */
    public static function provideConstructWillCreateNewInstanceGivenTimestampIsValid(): iterable
    {
        yield [1720510862];
    }

    /** @return iterable<array<int, int>> */
    public static function provideConstructWillThrowExceptionGivenTimestampIsInvalidCases(): iterable
    {
        yield [17205108621];
    }
}
