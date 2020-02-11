<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_post_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class PostTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
