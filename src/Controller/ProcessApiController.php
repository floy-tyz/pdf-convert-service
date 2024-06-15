<?php

namespace App\Controller;

use App\Bus\EventBusInterface;
use App\Service\Process\Event\SaveSourceFilesEvent;
use App\Service\File\Interface\FileManagerInterface;
use App\Service\Process\Factory\ProcessFactoryEvent;
use App\Traits\ResponseStatusTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProcessApiController extends AbstractController
{
    use ResponseStatusTrait;

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly EventBusInterface $eventBus,
        private readonly FileManagerInterface $fileManager,
        private readonly ProcessFactoryEvent $factoryEvent,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/api/v1/process', name: 'app.process.worker', methods: ['POST'])]
    public function convert(Request $request): Response
    {
        # todo
//        if (!$request->request->get('uuid') || !$request->request->get('extension')) {
//            throw new Exception('uuid and extension is required.');
//        }

        $tmpFilesPaths = $this->eventBus->publish(new SaveSourceFilesEvent(
                $request->files->all(),
            )
        );

        $process = $this->factoryEvent->createProcessEvent($request->request->get('key'));

        $this->messageBus->dispatch(new $process(
                $request->request->get('uuid'),
                $request->request->get('key'),
                $request->request->get('extension'),
                $request->request->all('context'),
                $tmpFilesPaths
            )
        );

        return $this->success();
    }
}
