<?php

namespace App\Service\Process\Event\External;

use App\Bus\AsyncInterface;

/**
 * External
 */
readonly class SaveProcessedFilesEvent implements AsyncInterface
{
    /**
     * @param string $processUuid
     * @param string $extension
     * @param array<string, string> $filesKeys
     */
    public function __construct(
        private string $processUuid,
        private string $extension,
        private array $filesKeys,
    ) {
    }

    public function getProcessUuid(): string
    {
        return $this->processUuid;
    }

    public function getFilesKeys(): array
    {
        return $this->filesKeys;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }
}