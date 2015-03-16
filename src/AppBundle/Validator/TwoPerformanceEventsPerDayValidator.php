<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class TwoPerformanceEventsPerDayValidator extends ConstraintValidator
{
    private $repository;
    private $translator;

    public function __construct(EntityRepository $repository, TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    public function validate($object, Constraint $constraint)
    {
        $conflicts = $this->repository->findAllByDate($object->getDateTime());

        if (count($conflicts) > 1) {
            $this->context->addViolationAt('dateTime', $this->translator->trans('twoPerformanceEventsValidError'));
        }
    }
}
