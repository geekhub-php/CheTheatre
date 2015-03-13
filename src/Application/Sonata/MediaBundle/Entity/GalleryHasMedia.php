<?php

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia as BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media__gallery_media")
 * @ORM\Entity
 */
class GalleryHasMedia extends BaseGalleryHasMedia
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
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string          $title
     * @return GalleryHasMedia
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param  string          $description
     * @return GalleryHasMedia
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
