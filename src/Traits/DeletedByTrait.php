<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeletedByTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $deletedBy;

    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;
    }
}
