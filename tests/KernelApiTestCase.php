<?php

declare(strict_types=1);

namespace App\Tests;

use GuzzleHttp\Psr7\Request;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
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
abstract class KernelApiTestCase extends WebTestCase implements EndpointKernelTestCase
{
    use EndpointKernelTestCaseTrait;

    protected static ValidatorBuilder $validatorBuilder;

    protected static KernelBrowser $client;

    /** @var array<string,string> */
    protected static array $accessToken = [];

    public static function setUpBeforeClass(): void
    {
        /** @var KernelBrowser $client */
        $client = static::getContainer()->get('test.client');
        self::$client = $client;

        self::$validatorBuilder = self::createValidatorBuilder();
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

    /** @param array<string, null|int|string> $queryParamKeyValues */
    public function parseQueryParameters(array $queryParamKeyValues): string
    {
        $queryParams = '?';

        foreach ($queryParamKeyValues as $param => $paramValue) {
            $queryParams .= $param . '=' . $paramValue . '&';
        }

        return rtrim($queryParams, '&');
    }

    protected function validatorBuilder(): ValidatorBuilder
    {
        return self::$validatorBuilder;
    }

    protected static function createValidatorBuilder(): ValidatorBuilder
    {
        return (new ValidatorBuilder())->fromYamlFile(__DIR__ . '/../docs/openApi/open_api_bundled.yaml');
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

    /**
     * @param array<mixed> $body
     *
     * @throws \JsonException
     */
    protected function prepareJsonBody(array $body): string
    {
        return json_encode($body, JSON_THROW_ON_ERROR);
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
