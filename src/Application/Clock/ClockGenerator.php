<?php

declare(strict_types=1);

namespace App\Application\Clock;

use App\Domain\Shared\VO\Clock;

interface ClockGenerator
{
    public function generateFromCurrentTime(): Clock;

    public function generateFromTimestamp(int $timestamp): Clock;
}
