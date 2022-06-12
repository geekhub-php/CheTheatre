<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_employee_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class EmployeeTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
