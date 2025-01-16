<?php

declare(strict_types=1);

namespace App\tests\API\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book;

use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\ListBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(ListBookController::class)]
#[Small]
final class ListBookControllerTest extends KernelApiTestCase
{
    public function testListBookControllerInvokeWillReturn200GivenRequestIsValid(): void
    {
        $request = new Request(
            self::GET,
            '/admin/v1/book',
            [],
        );

        $this->validateEndpoint(
            $request,
            '/admin/v1/book',
            Response::HTTP_OK,
        );
    }
}
