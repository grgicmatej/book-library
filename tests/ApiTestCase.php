<?php

declare(strict_types=1);

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Endpoint test case implementation where the real
 * request is made. It calls the given base URL and
 * makes requests to the given system. The environment
 * set behind the given URL will be an environment
 * in which tests will be run. The testing process
 * will have a test environment. Keep in mind that
 * tests run with this mode are slow. Local fixtures
 * aren't used because the remote URL is called.
 */
#[CoversNothing]
#[Small]
abstract class ApiTestCase extends TestCase implements EndpointTestCase
{
    use EndpointTestCaseTrait;

    protected static Client $client;

    /** @var array<string,string> */
    protected static array $accessToken = [];

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            'base_uri' => $_ENV['API_BASE_URL'],
            'http_errors' => false,
        ]);
        self::$validatorBuilder = self::createValidatorBuilder();
    }

    public function validateEndpoint(Request $request, string $path, int $expectedStatusCode): void
    {
        $this->validateRequest($request);

        try {
            $response = self::$client->send($request);
        } catch (GuzzleException $exception) {
            $response = match (true) {
                $exception instanceof BadResponseException => $exception->getResponse(),
                default => throw $exception,
            };
        }
        $this->validateOperation($request, $path, $response);

        static::assertSame($expectedStatusCode, $response->getStatusCode());
    }
}
