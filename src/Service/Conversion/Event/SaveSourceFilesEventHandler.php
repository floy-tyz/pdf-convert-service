<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventHandlerInterface;
use App\Service\File\Interface\FileManagerInterface;

readonly class SaveSourceFilesEventHandler implements EventHandlerInterface
{
    public function __construct(
        private FileManagerInterface $fileManager
    ) {
    }

    public function __invoke(SaveSourceFilesEvent $event): array
    {
        $tmpFilesPaths = [];

        foreach ($event->getSourceFiles() as $file) {

            $tmpFilePath = $this->fileManager->getTempDirectoryPath() . DIRECTORY_SEPARATOR . $file->getClientOriginalName();

            file_put_contents($tmpFilePath, file_get_contents($file->getPathname()));

            $tmpFilesPaths[] = $tmpFilePath;
        }

        return $tmpFilesPaths;
    }
}