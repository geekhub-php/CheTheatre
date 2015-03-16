<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class TwoPerformancePerDay extends Constraint
{
public function validatedBy()
{
    return 'two_performance_per_day';
}

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
