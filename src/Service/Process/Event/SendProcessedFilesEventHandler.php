<?php

namespace App\Service\Process\Event;

use App\Service\Process\Client\ProcessClientInterface;
use App\Service\File\Interface\FileManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendProcessedFilesEventHandler
{
    public function __construct(
        private ProcessClientInterface $client,
        private FileManagerInterface $fileManager,
    ) {
    }

    public function __invoke(SendProcessedFilesEvent $event): void
    {
        $this->client->requestSendProcessedFiles(
            $event->getProcessUuid(),
            $event->getFilesPaths()
        );
// todo
//        foreach ($event->getFilesPaths() as $filePath) {
//            $this->fileManager->remove(pathinfo($filePath, PATHINFO_FILENAME) . '.*');
//        }
    }
}