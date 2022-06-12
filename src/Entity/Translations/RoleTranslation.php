<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="role_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_role_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class RoleTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
