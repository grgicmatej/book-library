<?php

declare(strict_types=1);

use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\ListBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;

#[CoversClass(ListBookController::class)]
#[Small]
final class ListBookControllerTest extends KernelApiTestCase
{
    public function testListBookControllerInvokeWillReturnListOfBookObjects(): void
    {
        $request = new Request(
            self::GET,
            '/admin/v1/book',
            [],
        );

        $book = (string) $this->sendRequest($request)->getContent();
        self::assertJson($book);

        $decodedBook = (array) json_decode($book, true);
        self::assertArrayHasKey('data', $decodedBook);
        self::assertIsArray($decodedBook['data']);
        self::assertIsArray($decodedBook['data'][0]);
        self::assertArrayHasKey('attributes', $decodedBook['data'][0]);
        self::assertArrayHasKey('id', $decodedBook['data'][0]);
        self::assertArrayHasKey('type', $decodedBook['data'][0]);
        self::assertIsArray($decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('title', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('isbn', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('year', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('genre', $decodedBook['data'][0]['attributes']);
        self::assertIsArray($decodedBook['data'][0]['relationships']);
        self::assertArrayHasKey('authors', $decodedBook['data'][0]['relationships']);
        self::assertSame('book', $decodedBook['data'][0]['type']);
    }
}
