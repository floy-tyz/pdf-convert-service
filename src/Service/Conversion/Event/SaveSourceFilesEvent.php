<?php

namespace App\Service\Conversion\Event;

use App\Bus\EventInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class SaveSourceFilesEvent implements EventInterface
{
    /**
     * @param array<UploadedFile> $sourceFiles
     */
    public function __construct(
        private array $sourceFiles,
    ) {
    }

    public function getSourceFiles(): array
    {
        return $this->sourceFiles;
    }
}