<?php

declare(strict_types=1);

namespace App\Tests\API\Infrastructure\Driving\Http\Mobile\v1\Endpoint\HealthCheck;

use App\Infrastructure\Driving\Http\Mobile\v1\Endpoint\HealthCheck\GetHealthCheckController;
use App\Tests\KernelApiTestCase;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(GetHealthCheckController::class)]
class GetHealthCheckControllerTest extends KernelApiTestCase
{
    #[Group('KernelMobileApi')]
    public function testHealthCheckReturn200(): void
    {
        $request = new Request(
            self::GET,
            '/mobile/v1/healthcheck',
        );

        $this->validateEndpoint(
            $request,
            '/mobile/v1/healthcheck',
            Response::HTTP_OK,
        );
    }
}
