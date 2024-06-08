<?php

namespace App\Service\Process\Event;

use App\Bus\EventInterface;

readonly class OptimizePdfEvent implements EventInterface
{
    public function __construct(
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
}