<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_tag_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class TagTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
