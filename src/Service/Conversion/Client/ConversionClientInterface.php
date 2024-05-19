<?php

namespace App\Service\Conversion\Client;

interface ConversionClientInterface
{
    public function requestSendConvertedFiles(string $conversionUuid, array $convertedFilesPaths);
}