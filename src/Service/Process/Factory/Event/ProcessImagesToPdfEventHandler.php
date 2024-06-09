<?php

namespace App\Service\Process\Factory\Event;

use App\Bus\EventBusInterface;
use App\Service\Process\Event\ConvertTypeToTypeEvent;
use App\Service\Process\Event\MergeImagesToTypeEvent;
use App\Service\Process\Event\OptimizePdfEvent;
use App\Service\Process\Event\SendProcessedFilesEvent;
use App\Service\Process\Map\ProcessContextMap;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class ProcessImagesToPdfEventHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private EventBusInterface $eventBus,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ProcessImagesToPdfEvent $event): void
    {
        if (in_array(ProcessContextMap::MERGE, $event->getContext())) {
            $files = $this->eventBus->publish(new MergeImagesToTypeEvent(
                $event->getExtension(),
                $event->getFilesPaths()
            ));
        }
        else {
            $files = $this->eventBus->publish(new ConvertTypeToTypeEvent(
                $event->getExtension(),
                $event->getFilesPaths()
            ));
        }

        if (in_array(ProcessContextMap::OPTIMIZE, $event->getContext())) {
            $files = $this->eventBus->publish(new OptimizePdfEvent(
                $event->getFilesPaths()
            ));
        }

        $this->logger->debug("MESSAGES COUNT " . count($files));
        $this->logger->debug("MESSAGES DESTINATION " . implode(', ', $files));

        $this->messageBus->dispatch(new SendProcessedFilesEvent(
            $event->getProcessUuid(),
            $files,
        ));
    }
}