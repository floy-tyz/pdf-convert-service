<?php

namespace App\Service\Process\Event;

readonly class SendProcessedFilesEvent
{
    public function __construct(
        private string $processUuid,
        private array $filesPaths,
    ) {
    }

    public function getProcessUuid(): string
    {
        return $this->processUuid;
    }

    /**
     * @return array
     */
    public function getFilesPaths(): array
    {
        return $this->filesPaths;
    }
}