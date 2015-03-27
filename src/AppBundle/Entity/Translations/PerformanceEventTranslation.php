<?php

namespace AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="performance_schedule_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_performance_schedule_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class PerformanceEventTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PerformanceEvent", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
