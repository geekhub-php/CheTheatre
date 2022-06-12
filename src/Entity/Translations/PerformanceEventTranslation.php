<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\PerformanceEvent", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
