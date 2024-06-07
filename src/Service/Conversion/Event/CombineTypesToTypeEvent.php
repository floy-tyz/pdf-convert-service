<?php

namespace App\Service\Conversion\Event;

readonly class CombineTypesToTypeEvent
{
    public function __construct(
        private string $conversionUuid,
        private string $outputExtension,
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

    public function getOutputExtension(): string
    {
        return $this->outputExtension;
    }
}