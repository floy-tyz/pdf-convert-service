<?php

namespace App\Service\Process\Event\External;

use App\Bus\AsyncInterface;

/**
 * External
 */
readonly class ProcessFilesEvent implements AsyncInterface
{
    public function __construct(
        private string $processUuid,
        private array $filesUuids,
        private string $key,
        private string $extension,
        private array $context,
    ) {
    }

    public function getProcessUuid(): string
    {
        return $this->processUuid;
    }

    public function getFilesUuids(): array
    {
        return $this->filesUuids;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}