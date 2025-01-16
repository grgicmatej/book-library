<?php

declare(strict_types=1);


use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\AuthorFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\CreateBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(CreateBookController::class)]
#[Small]
final class CreateBookControllerTest extends KernelApiTestCase
{
    public function testCreateBookControllerInvokeWillCreateBookGivenRequestIsValid(): void
    {
        $body = [
            'data' => [
                'type' => 'book',
                'attributes' => [
                    'isbn' => '978-4-3956-2184-2',
                    'title' => 'Foo bar',
                    'year' => 1994,
                    'genre' => 'action',
                    'authorIds' => [AuthorFixture::ids()[0],
                    ],
                ],
            ]];

        $request = new Request(
            self::POST,
            '/admin/v1/book',
            $this->requestHeaders(),
            $this->prepareBody($body)
        );

        $book = ($this->sendRequest($request))->getContent();
        self::assertJson($book);

        $decodedBook = json_decode($book, true);
        $newId = $decodedBook['data']['id'];

        $this->checkCreatedBook($newId);
    }

    private function checkCreatedBook($id)
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
