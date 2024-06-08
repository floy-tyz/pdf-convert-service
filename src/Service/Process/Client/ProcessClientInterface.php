<?php

namespace App\Service\Process\Client;

interface ProcessClientInterface
{
    public function requestSendProcessedFiles(string $processUuid, array $processedFilesPaths);
}