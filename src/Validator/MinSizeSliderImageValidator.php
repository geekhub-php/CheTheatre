<?php

namespace App\Validator;

use App\Entity\Performance;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class MinSizeSliderImageValidator
 * @package App\Validator
 */
class MinSizeSliderImageValidator extends ConstraintValidator
{
    const MIN_HEIGHT = 500;
    const MIN_WIDTH = 1000;

    /**
     * @param Performance $object
     * @param Constraint                    $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if (!$this->isValid($object)) {
            $this->context->buildViolation($constraint->message)
                    ->atPath('sliderImage')
                    ->setParameter('{{width}}', self::MIN_HEIGHT)
                    ->setParameter('{{height}}', self::MIN_WIDTH)
                    ->addViolation()
            ;
        }
    }

    public function isValid(Performance $performance)
    {
        if ($performance->getSliderImage() === null) return false;
        if ($performance->getSliderImage()->getWidth() < self::MIN_WIDTH) return false;
        if ($performance->getSliderImage()->getHeight() < self::MIN_HEIGHT) return false;

        return true;
    }
}
