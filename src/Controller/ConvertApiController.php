<?php

namespace App\Controller;

use App\Bus\EventBusInterface;
use App\Service\Conversion\Event\CombineTypesToTypeEvent;
use App\Service\Conversion\Event\ConvertTypeToTypeEvent;
use App\Service\Conversion\Event\SaveSourceFilesEvent;
use App\Service\File\Interface\FileManagerInterface;
use App\Traits\ResponseStatusTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConvertApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly EventBusInterface $eventBus,
        private readonly FileManagerInterface $fileManager,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/api/v1/convert', name: 'app.unoconvert.convert', methods: ['POST'])]
    public function convert(Request $request): JsonResponse
    {
        if (!$request->request->get('uuid') || !$request->request->get('output_extension')) {
            throw new Exception('uuid and output_extension is required.');
        }

        $tmpFilesPaths = $this->eventBus->publish(new SaveSourceFilesEvent(
                $request->files->all(),
            )
        );

        $this->messageBus->dispatch(new ConvertTypeToTypeEvent(
                $request->request->get('uuid'),
                $request->request->get('output_extension'),
                $tmpFilesPaths
            )
        );

        return $this->success();
    }

    /**
     * @throws Exception
     */
    #[Route('/api/v1/combine', name: 'app.unoconvert.combine', methods: ['POST'])]
    public function combine(Request $request): JsonResponse
    {
        if (!$request->request->get('uuid') || !$request->request->get('output_extension')) {
            throw new Exception('uuid or output_extension is required.');
        }

        $tmpFilesPaths = $this->eventBus->publish(new SaveSourceFilesEvent(
                $request->files->all(),
            )
        );

        $this->messageBus->dispatch(new CombineTypesToTypeEvent(
                $request->request->get('uuid'),
                $request->request->get('output_extension'),
                $tmpFilesPaths
            )
        );

        return $this->success();
    }
}
