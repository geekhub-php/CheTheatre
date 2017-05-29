<?php

namespace AppBundle\Security;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * This is not part of security layer, because we decide to restrict access before security layer
 */
class IpVoter
{
    const ACCESS_GRANTED = 1;
    const ACCESS_DENIED = -1;

    protected $registry;

    protected $ipTable = null;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Request $request
     * @return int either ACCESS_GRANTED or ACCESS_DENIED
     */
    public function vote(Request $request)
    {
        if (true === $this->registry->getRepository('AppBundle:Client')->isBanned($request->getClientIp())) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }
}
