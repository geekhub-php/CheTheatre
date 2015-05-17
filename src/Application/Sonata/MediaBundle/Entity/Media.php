<?php

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media__media")
 * @ORM\Entity
 */
class Media extends BaseMedia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string")
     */
    public $createdAt;

    /**
     * @var Datetime
     *
     * @Expose
     * @Type("string")
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string")
     */
    public $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
