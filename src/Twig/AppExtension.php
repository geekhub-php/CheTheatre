<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use App\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('allTagsAsString', array($this, 'allTagsAsString')),
        );
    }

    /**
     * @return string
     */
    public function allTagsAsString()
    {
        $tagsAsString = '';

        foreach ($this->allTags() as $tag) {
            $tagsAsString .= sprintf('"%s",', str_replace('"', '\"', $tag->getTitle()));
        }

        return trim($tagsAsString, ',');
    }

    /**
     * @return Tag[]|array
     */
    protected function allTags()
    {
        return $this->em->getRepository(Tag::class)->findAll();
    }

    public function getName()
    {
        return 'app_extension';
    }
}
