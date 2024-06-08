<?php

namespace App\Service\Process\Event;

use App\Bus\EventHandlerInterface;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\Uno\UnoConvertInterface;

readonly class OptimizePdfEventHandler implements EventHandlerInterface
{
    public function __construct(
    ) {
    }

    public function __invoke(OptimizePdfEvent $event): array
    {
        // todo some logic to optimize pdf

        return $event->getFilesPaths();
    }
}