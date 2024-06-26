<?php

namespace App\Service\File\Manager;

use App\Service\File\Interface\FileManagerInterface;
use App\Service\File\Utils\Dir;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

class FileManager implements FileManagerInterface
{
    private const string TEMP_DIRECTORY_PATH = '/tmp';

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getAbsolutePath(string $filepath): string
    {
        return $this->parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . $filepath;
    }

    /**
     * @param string $fileUuid
     * @param string $filepath
     * @param string $filename
     * @param string|null $extension
     * @inheritDoc
     */
    public function moveFileToStorage(string $fileUuid, string $filepath, string $filename, ?string $extension): string
    {
        $baseDir = $this->parameterBag->get('kernel.project_dir');

        $relativeFilePath = $this->parameterBag->get('files_storage_dir') .
            DIRECTORY_SEPARATOR .
            (new DateTime())->format('Y/m/d/H/')
            . $fileUuid
            . '.'
            . ($extension ?: pathinfo($filepath, PATHINFO_EXTENSION));

        $absoluteMoveFilePath = $baseDir . DIRECTORY_SEPARATOR . $relativeFilePath;

        Dir::createDirectoryIfNotExist(dirname($absoluteMoveFilePath));

        (new Filesystem())->rename($filepath, $absoluteMoveFilePath);

        return $relativeFilePath;
    }

    /**
     * @inheritDoc
     */
    public function getTempDirectoryPath(): string
    {
        return self::TEMP_DIRECTORY_PATH;
    }

    /**
     * @inheritDoc
     */
    public function getTempFilePath(): string
    {
        $fileHandleResource = tmpfile();

        $metaData = stream_get_meta_data($fileHandleResource);

        return $metaData['uri'];
    }

    /**
     * @inheritDoc
     */
    public function remove(string $path): void
    {
        $filesystem = new Filesystem();

        try {
            $filesystem->remove($path);
        } catch (IOException $ioException) {
            $this->logger->info(
                'Ошибка при удаление файла или директории.',
                [
                    'message' => $ioException->getMessage(),
                ]
            );
        }
    }
}
