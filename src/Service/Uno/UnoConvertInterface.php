<?php

namespace App\Service\Uno;

interface UnoConvertInterface
{
    public function convert(string $sourceFilePath, string $destinationFilePath, array $context = []);
}