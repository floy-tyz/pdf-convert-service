<?php

namespace App\Service\Process\Client;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ProcessHttpClientInterface implements ProcessClientInterface
{

    public function __construct(
        private HttpClientInterface $processClient,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param string $processUuid
     * @param array $processedFilesPaths
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function requestSendProcessedFiles(string $processUuid, array $processedFilesPaths): string
    {
        $formFields = [
            'uuid' => $processUuid,
        ];

        foreach ($processedFilesPaths as $filePath) {
            $formFields[pathinfo($filePath, PATHINFO_FILENAME)] = DataPart::fromPath($filePath);
        }

        $formData = new FormDataPart($formFields);

        $this->logger->critical('FORMDATA' . serialize($formData));
        $this->logger->critical('FORMDATA', [$formData]);

        $content = $this->processClient->request(
            'POST',
            "/api/v1/process/$processUuid/files",
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return $content->getContent();
    }
}