<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

interface EntityManagerAwareInterface
{
    public function setEntityManager(EntityManagerInterface $em);
}
