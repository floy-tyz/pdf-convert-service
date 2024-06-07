<?php

namespace App\Service\Conversion\Event;

use App\Service\Conversion\Client\ConversionClientInterface;
use App\Service\File\Interface\FileManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendConvertedFilesEventHandler
{
    public function __construct(
        private ConversionClientInterface $client,
        private FileManagerInterface $fileManager,
    ) {
    }

    public function __invoke(SendConvertedFilesEvent $event): void
    {
        $this->client->requestSendConvertedFiles(
            $event->getConversionUuid(),
            $event->getFilesPaths()
        );

        foreach ($event->getFilesPaths() as $filePath) {
            $this->fileManager->remove(pathinfo($filePath, PATHINFO_FILENAME) . '.*');
        }
    }
}