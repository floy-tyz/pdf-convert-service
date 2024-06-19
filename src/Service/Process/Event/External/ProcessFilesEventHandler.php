<?php

namespace App\Service\Process\Event\External;

use App\Bus\AsyncBusInterface;
use App\Bus\AsyncHandlerInterface;
use App\Bus\EventBusInterface;
use App\Service\Process\Event\SaveSourceFilesEvent;
use App\Service\Process\Factory\ProcessFactoryEvent;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ProcessFilesEventHandler implements AsyncHandlerInterface
{
    public function __construct(
        private AsyncBusInterface $asyncBus,
        private EventBusInterface $eventBus,
        private ProcessFactoryEvent $factoryEvent,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ProcessFilesEvent $event): void
    {
        $filesPaths = $this->eventBus->publish(new SaveSourceFilesEvent($event->getFilesUuids()));

        $process = $this->factoryEvent->createProcessEvent($event->getKey());

        $this->asyncBus->dispatch(new $process(
                $event->getProcessUuid(),
                $event->getKey(),
                $event->getExtension(),
                $event->getContext(),
                $filesPaths
            )
        );
    }
}