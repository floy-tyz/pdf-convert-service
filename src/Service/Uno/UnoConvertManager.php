<?php

namespace App\Service\Uno;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UnoConvertManager implements UnoConvertInterface
{
    const UNOCONVERT = 'unoconvert';

    public function convert(string $sourceFilePath, string $destinationFilePath, array $context = []): void
    {
        $baseCommand = [self::UNOCONVERT, $sourceFilePath, $destinationFilePath];

        if (array_key_exists('filters', $context)) {
            $baseCommand = array_merge($baseCommand, $context['filters']);
        }

        $process = new Process($baseCommand);

        try {
            $process->mustRun();
        }
        catch (ProcessFailedException $exception) {
        }
    }
}