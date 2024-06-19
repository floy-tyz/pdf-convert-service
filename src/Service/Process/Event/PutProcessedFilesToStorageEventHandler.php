<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Service\Aws\S3\S3AdapterInterface;
use App\Service\File\Utils\Dir;
use Exception;
use Symfony\Component\Uid\Uuid;

readonly class PutProcessedFilesToStorageEventHandler implements EventHandlerInterface
{
    const string S3_BUCKET = 'processed-files';

    public function __construct(
        private S3AdapterInterface $s3Service
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(PutProcessedFilesToStorageEvent $event): array
    {
        $filesKeys = [];

        foreach ($event->getFilesPaths() as $filePath) {

            Dir::checkFileExist($filePath);

            $originalFileUuid = pathinfo($filePath, PATHINFO_FILENAME);
            $processedFileUuid = Uuid::v4();

            $this->s3Service->putObject(self::S3_BUCKET, $processedFileUuid, $filePath);

            $filesKeys[$originalFileUuid] = $processedFileUuid;
        }

        return $filesKeys;
    }
}