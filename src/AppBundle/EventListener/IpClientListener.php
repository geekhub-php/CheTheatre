<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ManagerRegistry;

class IpClientListener
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $client = $this->registry->getRepository('AppBundle:Client')
            ->findIpBanned($request->getClientIp());
        if ($client) {
            $response = new JsonResponse([
                'code' => 403,
                'message' => 'Forbidden. You\'re banned!',
            ], 403);
            $event->setResponse($response);
        }
    }
}
