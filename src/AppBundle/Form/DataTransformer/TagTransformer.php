<?php

namespace AppBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Translations\TagTranslation;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var
     */
    private $locale;

    /**
     * @param $locale
     * @param ObjectManager $om
     */
    public function __construct($locale, ObjectManager $om)
    {
        $this->locale = $locale;
        $this->om = $om;
    }

    public function transform($collection)
    {
        if (null === $collection) {
            return;
        }

        return implode(',', $collection->map(
            function (Tag $tag) { return $tag->getTitle(); }
        )->toArray());
    }

    public function reverseTransform($string)
    {
        if (!$string) {
            return;
        }

        $tags = new ArrayCollection();

        foreach (explode(',', $string) as $tagTitle) {
            $tag = $this->om->getRepository('AppBundle:Tag')->findOneByTitle($tagTitle);

            if (
                !$tag &&
                $tagTranslation = $this->om->getRepository('AppBundle:Translations\TagTranslation')->findOneByContent($tagTitle)
            ) {
                $tag = $tagTranslation->getObject();
            }

            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);

                $tagTranslation = new TagTranslation();
                $tagTranslation->setLocale('ua');
                $tagTranslation->setField('title');
                $tagTranslation->setContent($tagTitle);
                $tagTranslation->setObject($tag);

                $tag->addTranslation($tagTranslation);

                $this->om->persist($tagTranslation);

                $this->om->persist($tag);
            }

            $tags->add($tag);
        }

        return $tags;
    }
}
