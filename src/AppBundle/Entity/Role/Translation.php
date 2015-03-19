<?php

namespace AppBundle\Entity\Role;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_role_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_role_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class Translation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}