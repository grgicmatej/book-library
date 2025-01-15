<?php

declare(strict_types=1);

namespace App\Application\Bus;

use App\Application\Event\AsyncEvent;
use App\Application\Event\Event;

interface EventBus
{
    public function handleEvent(Event $event): void;

    public function handleEventAsync(AsyncEvent $event): void;

    public function isEventDispatched(string $event): bool;
}
