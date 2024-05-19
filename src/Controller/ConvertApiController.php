<?php

namespace App\Controller;

use App\Event\ConvertDocxToPdfEvent;
use App\Service\File\Interface\FileManagerInterface;
use App\Traits\ResponseStatusTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConvertApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly FileManagerInterface $fileManager,
    ) {
    }

    #[Route('/api/v1/convert', name: 'app_convert_api', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $tmpFilesPaths = [];

        /** @var UploadedFile $file */
        foreach ($request->files->all() as $file) {
            $tmpFilePath = $this->fileManager->getTempDirectoryPath() . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
            file_put_contents($tmpFilePath, file_get_contents($file->getPathname()));
            $tmpFilesPaths[] = $tmpFilePath;
        }

        $this->messageBus->dispatch(new ConvertDocxToPdfEvent(
                $request->request->get('uuid'),
                $tmpFilesPaths
            )
        );

        return $this->success();
    }
}
