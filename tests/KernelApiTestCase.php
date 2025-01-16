<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\AuthorFixture;
use App\Infrastructure\Driven\Persistence\Doctrine\Fixture\BookFixture;
use GuzzleHttp\Psr7\Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Small;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Endpoint test case implementation where
 * Symfony request simulator is used. It
 * creates a Kernel class and invokes
 * entire applications without making
 * HTTP calls over the network. By using
 * it entire application remains in the
 * test mode and because it never creates
 * API calls it is relatively fast.
 */
#[CoversNothing]
#[Small]
abstract class KernelApiTestCase extends WebTestCase implements EndpointTestCase
{
    use EndpointTestCaseTrait;

    protected static KernelBrowser $client;

    /** @var array<string,string> */
    protected static array $accessToken = [];

    protected static FixtureLoader $fixturesLoader;

    protected static bool $areFixturesLoaded = false;

    public static function setUpBeforeClass(): void
    {
        if (false === isset(static::$client)) {
            self::$client = static::createClient();
        }
        if (null === static::$kernel) {
            static::bootKernel();
        }
        self::$validatorBuilder = self::createValidatorBuilder();

        if (false === self::$areFixturesLoaded) {
            self::$fixturesLoader = new FixtureLoader(self::getContainer());
            self::$fixturesLoader->load(...self::fixturesToLoad());
            self::$areFixturesLoaded = true;
        }
    }

    protected function setUp(): void
    {
        if (null === static::$kernel) {
            static::bootKernel();
        }
    }

    public function validateEndpoint(
        Request $request,
        string $path,
        int $expectedStatusCode
    ): void {
        $this->validateRequest($request);
        $response = $this->sendRequest($request);

        $psr17Factory = new Psr17Factory();
        $psr17HttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        $this->validateOperation($request, $path, $psr17HttpFactory->createResponse($response));

        static::assertSame($expectedStatusCode, $response->getStatusCode());
    }

    /** @return array<int,string> */
    protected static function fixturesToLoad(): array
    {
        return [
            BookFixture::class,
            AuthorFixture::class,
        ];
    }

    protected static function getContainerInstance(): ContainerInterface
    {
        return static::getContainer();
    }

    protected function createRequest(
        string $method,
        string $path,
        ?string $accessToken = null,
        ?string $body = null
    ): Request {
        return new Request($method, $path, $this->requestHeaders($accessToken), $body);
    }

    protected function sendRequest(RequestInterface $request): SymfonyResponse
    {
        self::$client->request(
            $request->getMethod(),
            (string) $request->getUri(),
            $this->prepareQueryParameters($request),
            [],
            $this->prepareHeaders($request),
            (string) $request->getBody(),
        );

        return self::$client->getResponse();
    }

    /** @return array<string, string> */
    private function prepareHeaders(RequestInterface $request): array
    {
        $headers = $request->getHeaders();
        $preparedHeaders = [];

        foreach ($headers as $header => $value) {
            $fastCgiHeader = 'HTTP_' . mb_strtoupper(str_replace('-', '_', $header));

            $preparedHeaders[$fastCgiHeader] = $value[0];
        }

        return $preparedHeaders;
    }

    /** @return array<string,string> */
    private function prepareQueryParameters(RequestInterface $request): array
    {
        $query = parse_url((string) $request->getUri(), PHP_URL_QUERY);

        if (true === empty($query)) {
            return [];
        }

        return array_reduce(
            explode('&', $query),
            static function (array $a, string $current) {
                [$name, $value] = explode('=', $current);

                $a[$name] = urldecode($value);

                return $a;
            },
            [],
        );
    }
}
