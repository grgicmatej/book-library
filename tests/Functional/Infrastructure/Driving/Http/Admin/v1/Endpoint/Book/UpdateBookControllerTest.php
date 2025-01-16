<?php

declare(strict_types=1);


use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\AuthorFixture;
use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\UpdateBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(UpdateBookController::class)]
#[Small]
final class UpdateBookControllerTest extends KernelApiTestCase
{
    public function testUpdateBookControllerInvokeWillUpdateBookGivenRequestIsValid(): void
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

        $titleBeforeUpdate = $decodedBook['data']['attributes']['title'];
        $yearBeforeUpdate = $decodedBook['data']['attributes']['year'];
        $genreBeforeUpdate = $decodedBook['data']['attributes']['genre'];

        $body = [
            'data' => [
                'id' => BookFixture::ids()[0],
                'type' => 'book',
                'attributes' => [
                    'isbn' => '978-4-3956-2184-2',
                    'title' => 'Foo bar123',
                    'year' => 2005,
                    'genre' => 'horor',
                    'authorIds' => [AuthorFixture::ids()[0],
                    ],
                ],
            ]];

        $request = new Request(
            self::PATCH,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[0]),
            $this->requestHeaders(),
            $this->prepareBody($body)
        );

        $book = ($this->sendRequest($request))->getContent();
        self::assertJson($book);

        $decodedBook = json_decode($book, true);
        $newId = $decodedBook['data']['id'];

        $this->checkUpdatedBook($newId);

        self::assertNotSame($titleBeforeUpdate, $decodedBook['data']['attributes']['title']);
        self::assertNotSame($yearBeforeUpdate, $decodedBook['data']['attributes']['year']);
        self::assertNotSame($genreBeforeUpdate, $decodedBook['data']['attributes']['genre']);
    }

    private function checkUpdatedBook($id)
    {
        $request = new Request(
            self::GET,
            sprintf('/admin/v1/book/%s', $id),
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
        self::assertSame($id, $decodedBook['data']['id']);
    }
}