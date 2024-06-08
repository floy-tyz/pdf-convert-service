<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\Uno\UnoConvertInterface;

readonly class ConvertTypeToTypeEventHandler implements EventHandlerInterface
{
    public function __construct(
        private UnoConvertInterface $unoConvert,
        private FileManagerInterface $fileManager
    ) {
    }

    public function __invoke(ConvertTypeToTypeEvent $event): array
    {
        $convertedFiles = [];

        foreach ($event->getFilesPaths() as $filepath) {

            $destinationFilePath = $this->fileManager->getTempDirectoryPath()
                . DIRECTORY_SEPARATOR
                . pathinfo($filepath, PATHINFO_FILENAME)
                . "."
                . $event->getExtension();

            $this->unoConvert->convert($filepath, $destinationFilePath);

            $convertedFiles[] = $destinationFilePath;
        }

        return $convertedFiles;
    }
}