<?php

namespace AppBundle\EventListener;

use AppBundle\Security\IpVoter;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * It is used for forbid some clients by IP to access to the API
 * That kind of cients should be seted by admin dashboard
 */
class ForbidByIpListener
{
    /**
     * @var IpVoter
     */
    private $vouter;

    public function __construct(IpVoter $vouter)
    {
        $this->vouter = $vouter;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (IpVoter::ACCESS_DENIED === $this->vouter->vote($request)) {
            $response = new JsonResponse([
                'code' => 403,
                'message' => 'Forbidden. You\'re banned!',
            ], 403);
            $event->setResponse($response);
        }
    }
}
