<?php

namespace App\Service\Process\Factory\Event;

abstract readonly class AbstractProcessEvent
{
    public function __construct(
        protected string $processUuid,
        protected string $key,
        protected string $extension,
        protected array $context,
        protected array $filesPaths,
    ) {
    }

    public function getProcessUuid(): string
    {
        return $this->processUuid;
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

    public function getKey(): string
    {
        return $this->key;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}