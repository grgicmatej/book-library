<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Bus;

use App\Application\Bus\QueryBus;
use App\Application\Query\Query;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Undabot\SymfonyJsonApi\Model\Collection\ObjectCollection;

/** @implements QueryBus<ObjectCollection<int,object>>
 *
 * @phpstan-ignore-next-line
 */
final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @return ObjectCollection<int,object>
     *
     * @throws \Throwable
     *
     * @phpstan-ignore-next-line
     */
    public function handleQuery(Query $query): ObjectCollection
    {
        try {
            /** @phpstan-ignore-next-line  */
            /** @var ObjectCollection<int,object> $result */
            $result = $this->handle($query);

            return $result;
        } catch (HandlerFailedException $ex) {
            $exceptions = $ex->getWrappedExceptions();

            throw array_values($exceptions)[0];
        }
    }
}
