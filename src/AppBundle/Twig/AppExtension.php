<?php

namespace AppBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;

class AppExtension extends \Twig_Extension
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->registry = $managerRegistry;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('allTagsAsString', array($this, 'allTagsAsString')),
        );
    }

    /**
     * @return string
     */
    public function allTagsAsString()
    {
        $tagsAsString = '';

        foreach ($this->allTags() as $tag) {
            $tagsAsString .= sprintf('"%s",', $tag->getTitle());
        }

        return trim($tagsAsString, ',');
    }

    /**
     * @return \AppBundle\Entity\Tag[]|array
     */
    protected function allTags()
    {
        return $this->registry->getManager()->getRepository('AppBundle:Tag')->findAll();
    }

    public function getName()
    {
        return 'app_extension';
    }
}
