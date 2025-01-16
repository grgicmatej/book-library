<?php

declare(strict_types=1);

use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\GetBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;

#[CoversClass(GetBookController::class)]
#[Small]
final class GetBookControllerTest extends KernelApiTestCase
{
    public function testGetBookControllerInvokeWillReturnBookGivenBookExistInDatabase(): void
    {
        $request = new Request(
            self::GET,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[0]),
            [],
        );

        $book = (string) $this->sendRequest($request)->getContent();
        self::assertJson($book);

        $decodedBook = (array) json_decode($book, true);
        self::assertArrayHasKey('data', $decodedBook);
        self::assertIsArray($decodedBook['data']);
        self::assertArrayHasKey('type', $decodedBook['data']);
        self::assertArrayHasKey('id', $decodedBook['data']);
        self::assertIsArray($decodedBook['data']);
        self::assertArrayHasKey('attributes', $decodedBook['data']);
        self::assertIsArray($decodedBook['data']['attributes']);
        self::assertArrayHasKey('title', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('isbn', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('year', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('genre', $decodedBook['data']['attributes']);
        self::assertIsArray($decodedBook['data']['relationships']);
        self::assertArrayHasKey('authors', $decodedBook['data']['relationships']);
        self::assertSame('book', $decodedBook['data']['type']);
        self::assertSame(BookFixture::ids()[0], $decodedBook['data']['id']);
    }
}
