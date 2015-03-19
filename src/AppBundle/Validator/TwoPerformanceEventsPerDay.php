<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class TwoPerformanceEventsPerDay extends Constraint
{
    /**
     * @var string
     */
    public $message = 'you_cant_set_more_events_per_day';

    public function validatedBy()
    {
        return 'two_performance_events_per_day';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
