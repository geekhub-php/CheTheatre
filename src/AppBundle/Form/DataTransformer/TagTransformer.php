<?php

namespace AppBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function transform($collection)
    {
        if (null === $collection) {
            return null;
        }

        return implode(',', $collection->map(
            function (Tag $tag) { return $tag->getTitle(); }
        )->toArray());
    }

    public function reverseTransform($string)
    {
        if (!$string) {
            return null;
        }

        $tags = new ArrayCollection();

        foreach (explode(',', $string) as $tagTitle) {
            $tag = $this->om->getRepository('AppBundle:Tag')->findOneByTitle($tagTitle);

            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);

                $this->om->persist($tag);
            }

            $tags->add($tag);
        }

        return $tags;
    }
}
