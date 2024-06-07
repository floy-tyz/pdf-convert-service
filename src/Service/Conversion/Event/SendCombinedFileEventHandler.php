<?php

namespace App\Service\Conversion\Event;

use App\Service\Conversion\Client\ConversionClientInterface;
use App\Service\File\Interface\FileManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendCombinedFileEventHandler
{
    public function __construct(
        private ConversionClientInterface $client,
        private FileManagerInterface $fileManager,
    ) {
    }

    public function __invoke(SendCombinedFileEvent $event): void
    {
        $this->client->requestSendCombinedFile(
            $event->getConversionUuid(),
            $event->getCombinedFilePath()
        );

        $this->fileManager->remove($event->getCombinedFilePath());
    }
}