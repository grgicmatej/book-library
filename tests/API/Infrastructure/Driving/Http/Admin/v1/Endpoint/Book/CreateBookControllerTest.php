<?php

declare(strict_types=1);

namespace App\tests\API\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

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
    public function testCreateBookControllerInvokeWillReturn200GivenRequestIsValid(): void
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

        $this->validateEndpoint(
            $request,
            '/admin/v1/book',
            Response::HTTP_CREATED,
        );
    }
}
