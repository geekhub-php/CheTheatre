<?php

namespace App\Entity;

use App\Traits\DeletedByTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @Gedmo\TranslationEntity(class="App\Entity\Translations\PostTranslation")
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
     *     targetEntity="App\Entity\Translations\PostTranslation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @var \App\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="post_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     * )
     */
    protected $galleryHasMedia;

    /**
     * \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts", cascade={"persist"})
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
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     * @return self
     */
    public function addGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \App\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedion(\App\Entity\GalleryHasMedia $galleryHasMedia)
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
     * @param \App\Entity\Tag $tags
     * @return self
     */
    public function addTag(\App\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \App\Entity\Tag $tags
     */
    public function removeTag(\App\Entity\Tag $tags)
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
