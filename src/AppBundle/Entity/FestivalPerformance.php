<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use AppBundle\Validator\MinSizeSliderImage;

/**
 * Class FestivalPerformance
 * @package AppBundle\Entity
 * @ORM\Table(name="festival_performances")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\PerformanceTranslation")
 * @ExclusionPolicy("all")
 * @MinSizeSliderImage()
 */
class FestivalPerformance extends Performance
{}
