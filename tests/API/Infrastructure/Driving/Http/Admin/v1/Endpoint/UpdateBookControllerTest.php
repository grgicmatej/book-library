<?php

declare(strict_types=1);

namespace App\Tests\API\Infrastructure\Driving\Http\Admin\v1\Endpoint;

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
    public function testUpdateBookControllerInvokeWillReturn200GivenRequestIsValid(): void
    {
        $body = [
            'data' => [
                'id' => BookFixture::ids()[0],
                'type' => 'book',
                'attributes' => [
                    'isbn' => '978-4-3956-2184-2',
                    'title' => 'Foo bar1',
                    'year' => 1994,
                    'genre' => 'action',
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

        $this->validateEndpoint(
            $request,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[0]),
            Response::HTTP_OK,
        );
    }
}
