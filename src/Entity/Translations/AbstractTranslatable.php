<?php

namespace App\Entity\Translations;

/**
 * NEXT_MAJOR: Remove this file.
 *
 * This is your based class if you want to use default gedmo translation with everything in the same table
 * Not recommended if you have a lot of translations
 * (just brings Gedmo locale mapping).
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @deprecated since version 2.x, to be removed in 3.0. Create your own instead.
 * @see https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md
 */
abstract class AbstractTranslatable
{
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     *
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
}
