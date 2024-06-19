<?php

namespace App\Service\Process\Factory\Event;

use App\Bus\AsyncInterface;

abstract readonly class AbstractProcessEvent implements AsyncInterface
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