<?php

namespace App\EventListener;

use App\Service\Exception\ServiceException;
use App\Service\Exception\ServiceExceptionData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ServiceException) {
            $exceptionData = $exception->getExceptionData();

            $response = new JsonResponse($exceptionData->toArray());

            $event->setResponse($response);
        }
    }

}