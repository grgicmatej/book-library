<?php

declare(strict_types=1);

namespace App\Tests;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Small;

/**
 * Base interface for all implementations of
 * endpoint API tests. Endpoint test case
 * should test the entire endpoint with minimal
 * amount of mocking and can be implemented
 * with various strategies.
 */
#[CoversNothing]
#[Small]
interface EndpointTestCase
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const HEAD = 'HEAD';
    public const CONNECT = 'CONNECT';
    public const OPTIONS = 'OPTIONS';
    public const TRACE = 'TRACE';

    public function validateEndpoint(Request $request, string $path, int $expectedStatusCode): void;
}
