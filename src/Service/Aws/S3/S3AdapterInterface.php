<?php

namespace App\Service\Aws\S3;

interface S3AdapterInterface
{
    /**
     * @param string $bucketName
     * @param string $key
     * @param string $filePath
     * @return string
     */
    public function putObject(string $bucketName, string $key, string $filePath): string;

    /**
     * @param string $bucketName
     * @param string $key
     * @return string
     */
    public function getObjectContent(string $bucketName, string $key): string;

    /**
     * @param string $bucketName
     * @param string $key
     * @return array
     */
    public function getObjectHead(string $bucketName, string $key): array;

    /**
     * @param string $bucketName
     * @return bool
     */
    public function isBucketExists(string $bucketName): bool;

    /**
     * @param string $bucketName
     * @return bool
     */
    public function createBucket(string $bucketName): bool;
}