<?php

namespace App\Validator;

use App\Entity\PerformanceEvent;
use App\Repository\PerformanceEventRepository;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class TwoPerformanceEventsPerDayValidator extends ConstraintValidator
{
    const MAX_PERFORMANCE_EVENTS_PER_ONE_DAY = 10;

    /**
     * @var PerformanceEventRepository
     */
    private $repository;

    public function __construct(PerformanceEventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @param Constraint                         $constraint
     */
    public function validate($performanceEvent, Constraint $constraint)
    {
        if (false === is_object($performanceEvent->getDateTime())) {
            $this->context->buildViolation($constraint->performance_must_have_a_date)
                ->atPath('dateTime')
                ->addViolation()
            ;

            return;
        }

        if ($this->isMoreThanMax($performanceEvent)) {
            $this->context->buildViolation($constraint->max_performances_per_day)
                ->atPath('dateTime')
                ->setParameter('{{count}}', self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY)
                ->addViolation()
            ;
        }
    }

    /**
     * @param PerformanceEvent $performanceEvent
     * @return bool
     */
    protected function isMoreThanMax(PerformanceEvent $performanceEvent)
    {
        $from = clone $performanceEvent->getDateTime();
        $from->setTime(00, 00, 00);

        $to = clone $performanceEvent->getDateTime();
        $to->setTime(23, 59, 59);

        $countPerformanceEventsPerDate = count($this->repository->findByDateRangeAndSlug($from, $to));

        if ($performanceEvent->getId()) {
            return $countPerformanceEventsPerDate >= self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY +1;
        } else {
            return $countPerformanceEventsPerDate >= self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY;
        }
    }
}
