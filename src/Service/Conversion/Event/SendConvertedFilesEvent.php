<?php

namespace App\Service\Conversion\Event;

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