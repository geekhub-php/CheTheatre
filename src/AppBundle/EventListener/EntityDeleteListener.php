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

    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $token  = $this->tokenStorage->getToken();

        if (null == $token) {
            throw new AccessDeniedException('Only authorized users can delete entities');
        }

        $object = $args->getEntity();

        if (!method_exists($object, 'setDeletedBy')) {
            return;
        }

        $om     = $args->getEntityManager();
        $uow    = $om->getUnitOfWork();
        $meta = $om->getClassMetadata(get_class($object));
        $reflProp = $meta->getReflectionProperty('deletedBy');
        $oldValue = $reflProp->getValue($object);
        $reflProp->setValue($object, $token->getUser()->getUsername());

        $om->persist($object);
        $uow->propertyChanged($object, 'deletedBy', $oldValue, $token->getUser()->getUsername());
        $uow->scheduleExtraUpdate($object, array(
            'deletedBy' => array($oldValue, $token->getUser()->getUsername()),
        ));
    }
}
