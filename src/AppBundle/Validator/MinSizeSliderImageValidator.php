<?php

namespace AppBundle\Validator;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class MinSizeSliderImageValidator
 * @package AppBundle\Validator
 */
class MinSizeSliderImageValidator extends ConstraintValidator
{
    const MIN_HEIGHT = 500;
    const MIN_WIDTH = 1000;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \AppBundle\Entity\Performance $object
     * @param Constraint                    $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if (($object->getSliderImage()->getWidth() < self::MIN_WIDTH) or ($object->getSliderImage()->getHeight() < self::MIN_HEIGHT)) {
            $this->context->addViolationAt(
                'sliderImage',
                $this->translator->trans($constraint->message, ['%height%' => self::MIN_HEIGHT, '%width%' => self::MIN_WIDTH])
                )
            ;
        }
    }
}
