<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Bus;

use App\Application\Bus\EventBus;
use App\Application\Event\AsyncEvent;
use App\Application\Event\Event;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

final class MessengerEventBus implements EventBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->messageBus = $eventBus;
    }

    /** @throws \Throwable */
    public function handleEvent(Event $event): void
    {
        try {
            $this->handle($event);
        } catch (HandlerFailedException $ex) {
            $exceptions = $ex->getWrappedExceptions();

            throw array_values($exceptions)[0];
        }
    }

    /** @throws \Throwable */
    public function handleEventAsync(AsyncEvent $event): void
    {
        try {
            $this->handle($event);
        } catch (HandlerFailedException $ex) {
            $exceptions = $ex->getWrappedExceptions();

            throw array_values($exceptions)[0];
        }
    }

    public function isEventDispatched(string $event): bool
    {
        $messageBus = $this->messageBus;
        if (!$messageBus instanceof TraceableMessageBus) {
            return false;
        }
        $dispatchedMessages = $messageBus->getDispatchedMessages();
        foreach ($dispatchedMessages as $dispatchedMessage) {
            if (\get_class($dispatchedMessage['message']) === $event) {
                return true;
            }
        }

        return false;
    }
}
