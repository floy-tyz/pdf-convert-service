<?php

namespace App\Service\Process\Event\External;

use App\Bus\AsyncHandlerInterface;
use App\Bus\EventBusInterface;
use App\Service\Process\Event\GetSourceFilesPathsEvent;
use App\Service\Process\Factory\Event\AbstractProcessEvent;
use App\Service\Process\Factory\ProcessFactoryEvent;
use Exception;

readonly class ProcessFilesEventHandler implements AsyncHandlerInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private ProcessFactoryEvent $factoryEvent,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ProcessFilesEvent $event): void
    {
        $filesPaths = $this->eventBus->publish(new GetSourceFilesPathsEvent($event->getFilesUuids()));

        /** @var AbstractProcessEvent $process */
        $process = $this->factoryEvent->createProcessEvent($event->getKey());

        $this->eventBus->publish(new $process(
                $event->getProcessUuid(),
                $event->getKey(),
                $event->getExtension(),
                $event->getContext(),
                $filesPaths
            )
        );
    }
}