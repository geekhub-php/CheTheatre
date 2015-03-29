<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\TimestampableTrait;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class Post
{
    use TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Type("string")
     * @Expose
     */
    private $shortDescription;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Type("string")
     * @Expose
     */
    private $text;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="mainPicture_id", referencedColumnName="id")
     * @Expose
     */
    private $mainPicture;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @var \AppBundle\Entity\GalleryHasMedia
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\GalleryHasMedia", cascade={"persist"})
     * @ORM\JoinTable(name="post_galleryHasMedia",
     *     joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="galleryHasMedia_id",referencedColumnName="id")}
     *     )
     */
    private $galleryHasMedia;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Type("string")
     * @Expose
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag")
     * @Expose
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleryHasMedia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
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
     * @param  string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set shortDescription
     *
     * @param  string $shortDescription
     * @return Post
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Set text
     *
     * @param  string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param  string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return \AppBundle\Entity\Media
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * Set mainPicture
     *
     * @param  \AppBundle\Entity\Media $mainPicture
     * @return Post
     */
    public function setMainPicture(\AppBundle\Entity\Media $mainPicture = null)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Add tag
     *
     * @param  \AppBundle\Entity\Tag $tag
     * @return Post
     */
    public function addTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->addPost($this);

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
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
     * Add galleryHasMedia
     *
     * @param  \AppBundle\Entity\GalleryHasMedia $galleryHasMedia
     * @return Post
     */
    public function addGalleryHasMedia(\AppBundle\Entity\GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedia[] = $galleryHasMedia;

        return $this;
    }

    /**
     * Remove galleryHasMedia
     *
     * @param \AppBundle\Entity\GalleryHasMedia $galleryHasMedia
     */
    public function removeGalleryHasMedia(\AppBundle\Entity\GalleryHasMedia $galleryHasMedia)
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
}
