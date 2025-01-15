<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared\VO;

use App\Domain\Shared\VO\Clock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Clock::class)]
class ClockTest extends TestCase
{
    #[DataProvider('provideConstructWillCreateNewInstanceGivenTimestampIsValid')]
    public function testConstructWillCreateNewInstanceGivenTimestampIsValid(int $validTimestamp): void
    {
        $timestamp = new Clock($validTimestamp);

        static::assertInstanceOf(Clock::class, $timestamp);
        static::assertSame($validTimestamp, $timestamp->value());
    }

    #[DataProvider('provideConstructWillThrowExceptionGivenTimestampIsInvalidCases')]
    public function testConstructWillThrowExceptionGivenTimestampIsInvalid(int $invalidTimestamp): void
    {
        $this->expectException(\Exception::class);
        new Clock($invalidTimestamp);
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
