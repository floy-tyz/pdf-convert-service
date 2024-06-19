<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class SaveSourceFilesEvent implements EventInterface
{
    /**
     * @param array<string> $filesUuids
     */
    public function __construct(
        private array $filesUuids,
    ) {
    }

    public function getFilesUuids(): array
    {
        return $this->filesUuids;
    }
}