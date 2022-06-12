<?php

namespace App\Entity\Translations;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * NEXT_MAJOR: Remove this file.
 *
 * If you don't want to use trait, you can extend AbstractPersonalTranslatable instead.
 *
 * @deprecated since version 2.x, to be removed in 3.0. Create your own trait instead.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
trait PersonalTranslatableTrait
{
    use TranslatableTrait;

    /**
     * @return ArrayCollection
     *
     * @phpstan-return ArrayCollection<int, AbstractPersonalTranslation>
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $field
     * @param string $locale
     *
     * @return string|null
     */
    public function getTranslation($field, $locale)
    {
        foreach ($this->getTranslations() as $translation) {
            if (0 === strcmp($translation->getField(), $field) && 0 === strcmp($translation->getLocale(), $locale)) {
                return $translation->getContent();
            }
        }

        return null;
    }

    /**
     * @return $this
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }

        return $this;
    }
}
