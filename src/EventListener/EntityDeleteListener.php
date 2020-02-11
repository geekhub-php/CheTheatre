<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EntityDeleteListener implements EventSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            SoftDeleteableListener::PRE_SOFT_DELETE
        ];
    }

    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $token  = $this->tokenStorage->getToken();
        $object = $args->getEntity();
        $om     = $args->getEntityManager();
        $uow    = $om->getUnitOfWork();

        if (!method_exists($object, 'setDeletedBy')) {
            return;
        }

        if (null == $token) {
            throw new AccessDeniedException('Only authorized users can delete entities');
        }

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
