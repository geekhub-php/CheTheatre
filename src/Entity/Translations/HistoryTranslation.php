<?php

namespace App\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="history_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_history_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class HistoryTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
