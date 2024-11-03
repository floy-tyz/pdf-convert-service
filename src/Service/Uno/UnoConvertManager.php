<?php

namespace App\Service\Uno;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class UnoConvertManager implements UnoConvertInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    const string UNOCONVERT = 'unoconvert';

    public function convert(string $sourceFilePath, string $destinationFilePath, array $context = []): void
    {
        $baseCommand = [self::UNOCONVERT, '--convert-to', 'pdf'];

        $color = hexdec('6b6b6b');

        $context['filters'] = [
//            "WatermarkColor=$color",
//            'WatermarkRotateAngle=400',
//            'WatermarkFontName=OpenSans',
//            'TiledWatermark=watermark',
            'FormsType=1',
            'ExportFormFields=false',
            'IsSkipEmptyPages=true',
        ];

        if (array_key_exists('filters', $context)) {
            foreach ($context['filters'] as $filter) {
                $baseCommand = array_merge($baseCommand, ['--filter-option', $filter]);
            }
        }

        $baseCommand = array_merge($baseCommand, [$sourceFilePath, $destinationFilePath]);

        $process = new Process($baseCommand);

        try {
            $process->mustRun();
        }
        catch (ProcessFailedException $exception) {
            $this->logger->error($exception->getMessage());
            throw new ProcessFailedException($exception->getProcess());
        }
    }
}