<?php

declare(strict_types=1);

namespace App\Entity\Translations;

use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractPersonalTranslatable
{
    /**
     * @var ArrayCollection|AbstractPersonalTranslation[]
     * @phpstan-var ArrayCollection<int, AbstractPersonalTranslation>
     */
    protected $translations;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|AbstractPersonalTranslation[]
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
