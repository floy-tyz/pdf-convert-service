<?php

namespace App\Service\Process\Factory\Event;

use App\Bus\EventBusInterface;
use App\Service\Process\Event\ConvertTypeToTypeEvent;
use App\Service\Process\Event\OptimizePdfEvent;
use App\Service\Process\Event\SendProcessedFilesEvent;
use App\Service\Process\Map\ProcessContextMap;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class ProcessOfficeToPdfEventHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
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

        $this->messageBus->dispatch(new SendProcessedFilesEvent(
            $event->getProcessUuid(),
            $files,
        ));
    }
}