<?php

namespace AppBundle\Entity\Translations;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="rows_for_sale_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_rows_for_sale_translation_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class RowsForSaleTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RowsForSale", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
