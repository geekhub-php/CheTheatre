<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class MinSizeSliderImage extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Image_must_be_minimum_size';

    public function validatedBy()
    {
        return 'min_size_slider_image';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
