<?php

declare(strict_types=1);

namespace App\Bus;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class EventBus implements EventBusInterface
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $eventBus
    ) {
        $this->messageBus = $eventBus;
    }

    /**
     * @throws Throwable
     */
    public function publish(EventInterface $event): mixed
    {
        try {
            return $this->handle($event);
        } catch (HandlerFailedException $handlerFailedException) {
            /** @var array{0: Throwable} $exceptions */
            $exceptions = $handlerFailedException->getNestedExceptions();

            throw $exceptions[0];
        }
    }
}
