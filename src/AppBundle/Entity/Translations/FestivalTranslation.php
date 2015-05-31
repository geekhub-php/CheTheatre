<?php

namespace AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="festival_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_festival_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class FestivalPerformanceTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Festival", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
