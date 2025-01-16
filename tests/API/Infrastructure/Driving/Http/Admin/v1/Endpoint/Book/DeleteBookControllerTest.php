<?php

declare(strict_types=1);

namespace App\tests\API\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\GetBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GetBookController::class)]
#[Small]
final class DeleteBookControllerTest extends KernelApiTestCase
{
    public function testGetBookControllerInvokeWillReturn200GivenRequestIsValid(): void
    {
        $request = new Request(
            self::DELETE,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[1]),
            [],
        );

        $this->validateEndpoint(
            $request,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[1]),
            Response::HTTP_NO_CONTENT,
        );
    }
}
