<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Ticket;
use AppBundle\Exception\Ticket\PlaceArrangementException;
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

        $this->checkObject($object);

        if (!method_exists($object, 'setDeletedBy')) {
            return;
        }

        $om   = $args->getEntityManager();
        $uow  = $om->getUnitOfWork();
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

    protected function checkObject($object)
    {
        switch (true) {
            case $object instanceof Ticket:
                if (!$object->isRemovable()) {
                    throw new PlaceArrangementException(
                        sprintf(
                            'Impossible to remove ticket: %s. It has status: %s.',
                            $object->getId(),
                            $object->getStatus()
                        )
                    );
                }
                break;
            default:
        }
    }
}
