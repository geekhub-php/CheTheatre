<?php

namespace App\Entity\Translations;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation as GedmoAbstractPersonalTranslation;

/**
 * NEXT_MAJOR: Remove this file.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @deprecated since version 2.x, to be removed in 3.0. Create your own instead.
 * @see https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md : Personal translations
 */
class AbstractPersonalTranslation extends GedmoAbstractPersonalTranslation
{
    /**
     * Convenient constructor.
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale = null, $field = null, $value = null)
    {
        if (null !== $locale) {
            $this->setLocale($locale);
        }

        if (null !== $field) {
            $this->setField($field);
        }

        if (null !== $value) {
            $this->setContent($value);
        }
    }
}
