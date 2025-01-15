<?php

declare(strict_types=1);

namespace App\Infrastructure\Driving\Http\Mobile\v1\Endpoint\HealthCheck;

use Undabot\SymfonyJsonApi\Http\Model\Response\JsonApiHttpResponse;

final class GetHealthCheckController
{
    public function __invoke(): JsonApiHttpResponse
    {
        return new JsonApiHttpResponse('Api Online', 200);
    }
}
