<?php

namespace AppBundle\Entity\Employee;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_employee_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_employee_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class Translation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}