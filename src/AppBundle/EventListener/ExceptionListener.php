<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\Container;

class ExceptionListener
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container  $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return JsonResponse
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $this->container->get('request');
        $route = $request->get('_route');
        $exception = $event->getException();
        $data = array(
            'error' => array(
                'code' => 403,
                'message' => 'Forbidden. You don\'t have necessary permissions for the resource',
            ),
        );

        if ($exception->getMessage() == 'Full authentication is required to access this resource.') {
            if ($route == 'reserve_ticket' || $route == 'free_ticket' || $route == 'get_orders' || $route == 'get_order') {
                $response = new JsonResponse($data);
                $response->headers->set('X-Status-Code', 403);
                $event->setResponse($response);
            }
        }
    }
}
