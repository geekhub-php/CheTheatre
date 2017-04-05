<?php

namespace AppBundle\Repository;

interface BasicRepositoryInterface
{
    /**
     * @param object $entity
     * @param bool $doFlush
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function save($entity, $doFlush = true);
}
