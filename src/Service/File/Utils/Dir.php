<?php

namespace App\Service\File\Utils;

use Exception;

class Dir
{
    /**
     * @throws Exception
     */
    public static function checkFileExist(string $filepath): void
    {
        if (!file_exists($filepath)) {

            $fileName = basename($filepath);

            throw new Exception("File not found '$fileName'");
        }
    }

    public static function createDirectoryIfNotExist(string $dirPath): void
    {
        if (!is_dir($dirPath)){
            mkdir($dirPath, 0777, true);
        }
    }
}
