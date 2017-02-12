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
    private $defaultLocale;

    /**
     * @var array
     */
    private $localeCollection;

    /**
     * @param $defaultLocale
     * @param array         $localeCollection
     * @param ObjectManager $om
     */
    public function __construct($defaultLocale, array $localeCollection, ObjectManager $om)
    {
        $this->defaultLocale = $defaultLocale;
        $this->localeCollection = $localeCollection;
        $this->om = $om;
    }

    public function transform($collection)
    {
        if (null === $collection) {
            return;
        }

        return implode(',', $collection->map(
            function (Tag $tag) {
                return $tag->getTitle();
            }
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
            $tagTranslation = $this->om
                ->getRepository('AppBundle:Translations\TagTranslation')
                ->findOneByContent($tagTitle);

            if (!$tag && $tagTranslation) {
                $tag = $tagTranslation->getObject();
            }

            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);
                $tag->setLocale($this->defaultLocale);

                foreach ($this->localeCollection as $locale) {
                    if ($locale !== $this->defaultLocale) {
                        $tagTranslation = new TagTranslation();
                        $tagTranslation->setLocale($locale);
                        $tagTranslation->setField('title');
                        $tagTranslation->setContent($tagTitle);
                        $tagTranslation->setObject($tag);

                        $tag->addTranslation($tagTranslation);
                        $this->om->persist($tagTranslation);
                    }
                }

                $this->om->persist($tag);
            }

            $tags->add($tag);
        }

        return $tags;
    }
}
