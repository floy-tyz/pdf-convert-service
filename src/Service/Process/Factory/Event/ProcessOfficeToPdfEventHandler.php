<?php

namespace App\Service\Process\Factory\Event;

use App\Bus\AsyncBusInterface;
use App\Bus\AsyncHandlerInterface;
use App\Bus\EventBusInterface;
use App\Service\Process\Event\ConvertTypeToTypeEvent;
use App\Service\Process\Event\External\SaveProcessedFilesEvent;
use App\Service\Process\Event\OptimizePdfEvent;
use App\Service\Process\Event\PutProcessedFilesToStorageEvent;
use App\Service\Process\Map\ProcessContextMap;

readonly class ProcessOfficeToPdfEventHandler implements AsyncHandlerInterface
{
    public function __construct(
        private AsyncBusInterface $asyncBus,
        private EventBusInterface $eventBus
    ) {
    }

    public function __invoke(ProcessOfficeToPdfEvent $event): void
    {
        $files = $this->eventBus->publish(new ConvertTypeToTypeEvent(
            $event->getExtension(),
            $event->getFilesPaths()
        ));

        if (in_array(ProcessContextMap::OPTIMIZE, $event->getContext())) {
            $files = $this->eventBus->publish(new OptimizePdfEvent(
                $event->getFilesPaths()
            ));
        }

        $filesKeys = $this->eventBus->publish(new PutProcessedFilesToStorageEvent($files));

        $this->asyncBus->dispatch(new SaveProcessedFilesEvent(
            $event->getProcessUuid(),
            $event->getExtension(),
            $filesKeys,
        ));
    }
}