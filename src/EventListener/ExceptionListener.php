<?php

namespace App\EventListener;

use App\Exception\BusinessException;
use App\Traits\ResponseStatusTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionListener
{
    use ResponseStatusTrait;

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof MethodNotAllowedHttpException) {

            $response = new Response();

            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            $event->setResponse($response);
        }

        /** @var BusinessException $exception */
        if ($exception instanceof BusinessException) {

            $event->allowCustomResponseCode();

            $response = $this->failed([ 'errors' => $exception->getErrors() ]);

            $event->setResponse($response);

            $event->stopPropagation();
        }
    }
}