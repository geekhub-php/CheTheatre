<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class TwoPerformanceEventsPerDay extends Constraint
{
    /**
     * @var string
     */
    public $maxPerformancesPerDay = 'you_cant_set_more_events_per_day';

    /**
     * @var string
     */
    public $performanceMustHaveDate = 'performance_must_have_a_date';

    public function validatedBy()
    {
        return 'two_performance_events_per_day';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
