<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class SendConvertedFilesEvent
{
    public function __construct(
        private string $conversionUuid,
        private array $filesPaths,
    ) {
    }

    public function getConversionUuid(): string
    {
        return $this->conversionUuid;
    }

    /**
     * @return array
     */
    public function getFilesPaths(): array
    {
        return $this->filesPaths;
    }
}