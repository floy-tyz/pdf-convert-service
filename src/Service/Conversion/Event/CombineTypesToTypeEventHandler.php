<?php

namespace App\Service\Conversion\Event;

use App\Service\File\Interface\FileManagerInterface;
use App\Service\Uno\UnoConvertInterface;
use Imagick;
use ImagickException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
readonly class CombineTypesToTypeEventHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private FileManagerInterface $fileManager
    ) {
    }

    /**
     * @throws ImagickException
     */
    public function __invoke(CombineTypesToTypeEvent $event): void
    {
        $destinationFilePath = $this->fileManager->getTempDirectoryPath()
            . DIRECTORY_SEPARATOR
            . Uuid::v4()
            . "."
            . $event->getOutputExtension();

        $generalFile = new Imagick();

        $generalFile->setFormat($event->getOutputExtension());

        foreach ($event->getFilesPaths() as $filesPath) {
            $image = new Imagick($filesPath);
            $generalFile->addImage($image);
        }

        $generalFile->writeImages($destinationFilePath, true);

        $this->messageBus->dispatch(new SendCombinedFileEvent(
            $event->getConversionUuid(),
            $destinationFilePath,
        ));
    }
}