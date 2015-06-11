<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EntityDeleteListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $token = $this->tokenStorage->getToken();
        $entity = $args->getEntity();

        if (null == $token) {
            throw new AccessDeniedException('Only authorized users can delete entities');
        }

        if (!method_exists($entity, 'setDeletedBy')) {
            return;
        }

        $entity->setDeletedBy($token->getUser()->getUsername());
        $args->getEntityManager()->persist($entity);
        $args->getEntityManager()->flush($entity);
    }
}
