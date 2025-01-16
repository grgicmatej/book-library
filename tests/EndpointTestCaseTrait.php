<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Shared\Exception\InvalidArgumentTranslatableException;
use Assert\Assertion;
use GuzzleHttp\Psr7\Request;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Small;
use Psr\Http\Message\ResponseInterface;

/**
 * Minimal set of functionalities need by any endpoint test case.
 */
#[CoversNothing]
#[Small]
trait EndpointTestCaseTrait
{
    protected static ValidatorBuilder $validatorBuilder;

    /** @return array<string,string> */
    public function requestHeaders(?string $token = null, string $contentType = 'application/json'): array
    {
        $headers = [
            'Content-Type' => $contentType,
        ];

        return $headers;
    }

    protected function validatorBuilder(): ValidatorBuilder
    {
        return self::$validatorBuilder;
    }

    protected static function createValidatorBuilder(): ValidatorBuilder
    {
        return (new ValidatorBuilder())->fromYamlFile(__DIR__ . '/../docs/openApi/open_api.yaml');
    }

    /** @param array<string,mixed> $body */
    protected function prepareBody(array $body): string
    {
        $encodedBody = json_encode($body, JSON_THROW_ON_ERROR);

        try {
            Assertion::isJsonString($encodedBody, 'errors.invalid_argument.body_must_be_json_string');
        } catch (\Throwable $e) {
            throw new InvalidArgumentTranslatableException($e->getMessage());
        }

        return $encodedBody;
    }

    protected function validateOperation(Request $request, string $path, ResponseInterface $response): void
    {
        $responseValidator = $this->validatorBuilder()->getResponseValidator();

        $operation = new OperationAddress($path, mb_strtolower($request->getMethod()));
        $responseValidator->validate($operation, $response);
    }

    protected function validateRequest(Request $request): void
    {
        $this->validatorBuilder()->getRequestValidator()->validate($request);
    }

    protected function parseAccessTokenFromResponse(ResponseInterface $response): string
    {
        /** @var array<string, mixed> $json */
        $json = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (false === \array_key_exists('access_token', $json)) {
            throw new \DomainException('No access_token found in response body.');
        }

        if (false === \is_string($json['access_token'])) {
            throw new \DomainException('Expected access_token to be string.');
        }

        return $json['access_token'];
    }
}
