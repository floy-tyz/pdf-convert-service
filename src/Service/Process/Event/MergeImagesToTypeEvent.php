<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;

readonly class MergeImagesToTypeEvent implements EventInterface
{
    public function __construct(
        private string $extension,
        private array $filesPaths,
    ) {
    }

    /**
     * @return array<string>
     */
    public function getFilesPaths(): array
    {
        return $this->filesPaths;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }
}