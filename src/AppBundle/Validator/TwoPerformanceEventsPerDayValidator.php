<?php

namespace AppBundle\Validator;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Repository\PerformanceEventRepository;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class TwoPerformanceEventsPerDayValidator extends ConstraintValidator
{
    const MAX_PERFORMANCE_EVENTS_PER_ONE_DAY = 2;

    /**
     * @var PerformanceEventRepository
     */
    private $repository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(PerformanceEventRepository $repository, TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    /**
     * @param \AppBundle\Entity\PerformanceEvent $performanceEvent
     * @param Constraint                         $constraint
     */
    public function validate($performanceEvent, Constraint $constraint)
    {
        if (false === is_object($performanceEvent->getDateTime())) {
            $this->context->addViolationAt(
                'dateTime',
                $this->translator->trans($constraint->performanceMustHaveDate)
            );

            return;
        }

        if ($this->isMoreThanMax($performanceEvent)) {
            $this->context->addViolationAt(
                'dateTime',
                $this->translator->trans(
                    $constraint->maxPerformancesPerDay,
                    ['%count%' => self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]
                )
            );
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
        }

        return $countPerformanceEventsPerDate >= self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY;
    }
}
