<?php

namespace App\Service\Conversion\Event;

readonly class SendCombinedFileEvent
{
    public function __construct(
        private string $conversionUuid,
        private string $combinedFilePath,
    ) {
    }

    public function getConversionUuid(): string
    {
        return $this->conversionUuid;
    }

    public function getCombinedFilePath(): string
    {
        return $this->combinedFilePath;
    }
}