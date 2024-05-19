<?php

namespace App\Event;

use App\Service\File\Interface\FileManagerInterface;
use App\Service\Uno\UnoConvertInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class ConvertDocxToPdfEventHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private UnoConvertInterface $unoConvert,
        private FileManagerInterface $fileManager
    ) {
    }

    public function __invoke(ConvertDocxToPdfEvent $event): void
    {
        $convertedFiles = [];

        foreach ($event->getFilesPaths() as $filepath) {
            $destinationFilePath = $this->fileManager->getTempDirectoryPath()
                . DIRECTORY_SEPARATOR
                . pathinfo($filepath, PATHINFO_FILENAME)
                . '.pdf';
            $this->unoConvert->convert($filepath, $destinationFilePath);
            $convertedFiles[] = $destinationFilePath;
        }

        $this->messageBus->dispatch(new SendConvertedFilesEvent(
            $event->getConversionUuid(),
            $convertedFiles,
        ));
    }
}