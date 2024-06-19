<?php

declare(strict_types=1);

namespace App\Bus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final readonly class AsyncBus implements AsyncBusInterface
{
    public function __construct(
        private MessageBusInterface $asyncBus
    ) {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(AsyncInterface $command): Envelope
    {
        return $this->asyncBus->dispatch($command);
    }
}
