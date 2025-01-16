<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Driven\Validator;

use App\Infrastructure\Driven\Validator\IsbnValueValidator;
use Isbn\Isbn;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IsbnValueValidator::class)]
class IsbnValueValidatorTest extends TestCase
{
    public function testValidateIsbnSuccess(): void
    {
        $isbn = new Isbn();
        $isbnValue = '978-3-16-148410-0';

        $isbnValue = $isbn->hyphens->removeHyphens($isbnValue);
        static::assertSame('9783161484100', $isbnValue);
        static::assertTrue($isbn->validation->isbn($isbnValue));
    }

    public function testValidateIsbnFailure(): void
    {
        $isbn = new Isbn();
        $isbnValue = '978-3-16-148410-0';

        $isbnValue = $isbn->hyphens->removeHyphens($isbnValue);
        static::assertSame('9783161484100', $isbnValue);
        static::assertTrue($isbn->validation->isbn($isbnValue));
    }
}
