<?php

declare(strict_types=1);


use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\GetBookController;
use App\Tests\KernelApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

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

        $book = ($this->sendRequest($request))->getContent();
        self::assertJson($book);

        $decodedBook = json_decode($book, true);
        self::assertArrayHasKey('data', $decodedBook);
        self::assertArrayHasKey('type', $decodedBook['data']);
        self::assertArrayHasKey('id', $decodedBook['data']);
        self::assertArrayHasKey('attributes', $decodedBook['data']);
        self::assertArrayHasKey('title', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('isbn', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('year', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('genre', $decodedBook['data']['attributes']);
        self::assertArrayHasKey('authors', $decodedBook['data']['relationships']);
        self::assertSame('book', $decodedBook['data']['type']);
        self::assertSame(BookFixture::ids()[0], $decodedBook['data']['id']);
    }
}
