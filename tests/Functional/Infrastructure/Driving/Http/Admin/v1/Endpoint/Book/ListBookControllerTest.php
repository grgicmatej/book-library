<?php

declare(strict_types=1);


use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\ListBookController;
use App\Tests\KernelApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

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

        $book = ($this->sendRequest($request))->getContent();
        self::assertJson($book);

        $decodedBook = json_decode($book, true);
        self::assertArrayHasKey('data', $decodedBook);
        self::assertIsArray($decodedBook['data']);
        self::assertArrayHasKey('attributes', $decodedBook['data'][0]);
        self::assertArrayHasKey('id', $decodedBook['data'][0]);
        self::assertArrayHasKey('type', $decodedBook['data'][0]);
        self::assertArrayHasKey('title', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('isbn', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('year', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('genre', $decodedBook['data'][0]['attributes']);
        self::assertArrayHasKey('authors', $decodedBook['data'][0]['relationships']);
        self::assertSame('book', $decodedBook['data'][0]['type']);
    }
}
