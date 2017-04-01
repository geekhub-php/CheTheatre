<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof AuthenticationException) {
            $response = new JsonResponse([
                'code' => $exception->getPrevious()->getCode(),
                'message' => $exception->getMessage()
            ], 403);
            $event->setResponse($response);
        }
    }
}
