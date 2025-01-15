<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Clock;

use App\Application\Clock\ClockGenerator;
use App\Domain\Shared\VO\Clock;
use Carbon\CarbonImmutable;

final class SystemTimeClock implements ClockGenerator
{
    public function generateFromCurrentTime(): Clock
    {
        return new Clock((new CarbonImmutable())->getTimestamp());
    }

    public function generateFromTimestamp(int $timestamp): Clock
    {
        return new Clock($timestamp);
    }
}
