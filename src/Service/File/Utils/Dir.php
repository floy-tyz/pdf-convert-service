<?php

namespace App\Service\File\Utils;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Dir
{
    public static function checkFileExist(string $filepath): void
    {
        if (!file_exists($filepath)) {

            $fileName = basename($filepath);

            throw new FileException("Не удалось найти файл '{$fileName}'");
        }
    }

    public static function createDirectoryIfNotExist(string $dirPath): void
    {
        if (!is_dir($dirPath)){
            mkdir($dirPath, 0777, true);
        }
    }
}
