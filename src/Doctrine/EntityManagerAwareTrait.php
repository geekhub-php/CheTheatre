<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerAwareTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function setEntityManager(EntityManagerInterface $em = null)
    {
        $this->em = $em;
    }
}
