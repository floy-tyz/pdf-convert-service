<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Service\Aws\S3\S3AdapterInterface;
use App\Service\File\Interface\FileManagerInterface;

readonly class GetSourceFilesPathsEventHandler implements EventHandlerInterface
{
    const string S3_BUCKET = 'non-processed-files';

    public function __construct(
        private FileManagerInterface $fileManager,
        private S3AdapterInterface $s3Service
    ) {
    }

    public function __invoke(GetSourceFilesPathsEvent $event): array
    {
        $tmpFilesPaths = [];

        foreach ($event->getFilesUuids() as $file) {

            $tmpFilePath = $this->fileManager->getTempDirectoryPath() . DIRECTORY_SEPARATOR . $file;

            file_put_contents($tmpFilePath, $this->s3Service->getObjectContent(self::S3_BUCKET, $file));

            $tmpFilesPaths[] = $tmpFilePath;
        }

        return $tmpFilesPaths;
    }
}