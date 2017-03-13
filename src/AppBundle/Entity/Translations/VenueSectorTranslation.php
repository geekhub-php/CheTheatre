<?php

namespace AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="venue_sector_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_venue_sector_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class VenueSectorTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VenueSector", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
