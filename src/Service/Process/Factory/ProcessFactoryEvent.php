<?php

namespace App\Service\Process\Factory;

use App\Service\Process\Factory\Event\ProcessImagesToPdfEvent;
use App\Service\Process\Factory\Event\ProcessOfficeToPdfEvent;
use App\Service\Process\Map\ProcessMap;
use Exception;

class ProcessFactoryEvent
{
    const array PROCESS_EVENT_MAP = [
        ProcessMap::OFFICE_TO_PDF => ProcessOfficeToPdfEvent::class,
        ProcessMap::IMG_TO_PDF => ProcessImagesToPdfEvent::class,
    ];

    /**
     * @throws Exception
     */
    public function createProcessEvent(string $key): string
    {
        if (array_key_exists($key, self::PROCESS_EVENT_MAP)) {
            return self::PROCESS_EVENT_MAP[$key];
        }

        throw new Exception('Unknown process event: ' . $key);
    }
}