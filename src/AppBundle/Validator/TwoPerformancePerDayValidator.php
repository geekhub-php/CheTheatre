<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class TwoPerformancePerDayValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($object, Constraint $constraint)
    {
        $date = $object->getPremiere()->format('Y-m-d');
        $conflicts = $this->em
            ->getRepository('AppBundle:Performance')
            ->findByDate($date)
        ;

        if (count($conflicts) > 1) {
            $this->context->addViolationAt('premiere', 'There is already two preformance premieres at this date!');
        }
    }
}