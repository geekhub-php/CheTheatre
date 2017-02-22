<?php

namespace AppBundle\Entity;

use AppBundle\Traits\DeletedByTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\PostTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Serializer\ExclusionPolicy("all")
 */
class Post extends AbstractTranslateableStory
{
    use DeletedByTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Translations\PostTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="post_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     * )
     */
    protected $galleryHasMedia;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="posts", cascade={"persist"})
     * @Serializer\Expose
     */
    protected $tags;

    /**
     * @var bool
     *
     * @ORM\Column(name="pinned", type="boolean")
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     */
    protected $pinned = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->galleryHasMedia = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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
     * Unset translations
     *
     * @return Post
     */
    public function unsetTranslations()
    {
        $this->translations = null;

        return $this;
    }

    /**
     * Add galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     * @return self
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia->removeElement($galleryHasMedia);
    }

    /**
     * Get galleryHasMedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryHasMedia()
    {
        return $this->galleryHasMedia;
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tags
     * @return self
     */
    public function addTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param boolean $pinned
     * @return Post
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPinned()
    {
        return $this->pinned;
    }
}
