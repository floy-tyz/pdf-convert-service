<?php

namespace App\Event;

readonly class ConvertDocxToPdfEvent
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
     * @return array<string>
     */
    public function getFilesPaths(): array
    {
        return $this->filesPaths;
    }
}