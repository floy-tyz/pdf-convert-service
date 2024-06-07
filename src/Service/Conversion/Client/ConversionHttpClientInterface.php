<?php

namespace App\Service\Conversion\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ConversionHttpClientInterface implements ConversionClientInterface
{

    public function __construct(
        private HttpClientInterface $conversionClient,
    ) {
    }

    /**
     * @param string $conversionUuid
     * @param array $convertedFilesPaths
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function requestSendConvertedFiles(string $conversionUuid, array $convertedFilesPaths): string
    {
        $formFields = [
            'uuid' => $conversionUuid,
        ];

        foreach ($convertedFilesPaths as $filePath) {
            $formFields[pathinfo($filePath, PATHINFO_FILENAME)] = DataPart::fromPath($filePath);
        }

        $formData = new FormDataPart($formFields);

        $content = $this->conversionClient->request(
            'POST',
            "/api/v1/conversion/$conversionUuid/files/converted",
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return $content->getContent();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function requestSendCombinedFile(string $conversionUuid, string $combinedFilePath): string
    {
        $formFields = [
            'uuid' => $conversionUuid,
        ];

        $formFields[pathinfo($combinedFilePath, PATHINFO_FILENAME)] = DataPart::fromPath($combinedFilePath);

        $formData = new FormDataPart($formFields);

        $content = $this->conversionClient->request(
            'POST',
            "/api/v1/conversion/$conversionUuid/files/combined",
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );

        return $content->getContent();
    }
}