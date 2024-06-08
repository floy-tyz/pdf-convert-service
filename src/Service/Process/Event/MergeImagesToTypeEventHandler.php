<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Service\File\Interface\FileManagerInterface;
use Imagick;
use ImagickException;
use Symfony\Component\Uid\Uuid;

readonly class MergeImagesToTypeEventHandler  implements EventHandlerInterface
{
    public function __construct(
        private FileManagerInterface $fileManager
    ) {
    }

    /**
     * @throws ImagickException
     */
    public function __invoke(MergeImagesToTypeEvent $event): array
    {
        $destinationFilePath = $this->fileManager->getTempDirectoryPath()
            . DIRECTORY_SEPARATOR
            . Uuid::v4()
            . "."
            . $event->getExtension();

        $generalFile = new Imagick();

        $generalFile->setFormat($event->getExtension());

        foreach ($event->getFilesPaths() as $filesPath) {
            $image = new Imagick($filesPath);
            $generalFile->addImage($image);
        }

        $generalFile->writeImages($destinationFilePath, true);

        return [$destinationFilePath];
    }
}