<?php

declare(strict_types=1);

namespace App\Tests\API\Infrastructure\Driving\Http\Admin\v1\Endpoint;

use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use App\Infrastructure\Driving\Http\Admin\v1\Endpoint\Book\GetBookController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GetBookController::class)]
#[Small]
final class GetBookControllerTest extends KernelApiTestCase
{
    public function testGetBookControllerInvokeWillReturn200GivenRequestIsValid(): void
    {
        $request = new Request(
            self::GET,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[0]),
            [],
        );

        $this->validateEndpoint(
            $request,
            sprintf('/admin/v1/book/%s', BookFixture::ids()[0]),
            Response::HTTP_OK,
        );
    }
}
