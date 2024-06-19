<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class PutProcessedFilesToStorageEvent implements EventInterface
{
    /**
     * @param array<string> $filesPaths
     */
    public function __construct(
        private array $filesPaths,
    ) {
    }

    public function getFilesPaths(): array
    {
        return $this->filesPaths;
    }
}