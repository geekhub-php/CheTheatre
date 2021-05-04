<?php

namespace App\Validator;

use App\Entity\Performance;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProducerValidator extends ConstraintValidator
{
    /**
     * @param Performance $performance
     * @param Constraint $constraint
     */
    public function validate($performance, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\ProducerConstraint */

        if ($performance->getExtProducer() && $performance->getProducer()) {
            $this->context
                ->buildViolation($constraint->ambiguousProducer)
                ->atPath('producer')
                ->addViolation();
            $this->context
                ->buildViolation($constraint->ambiguousProducer)
                ->atPath('extProducer')
                ->addViolation();
        }

        if (!$performance->getExtProducer() && !$performance->getProducer()) {
            $this->context
                ->buildViolation($constraint->noProducer)
                ->atPath('producer')
                ->addViolation();
        }
    }
}
