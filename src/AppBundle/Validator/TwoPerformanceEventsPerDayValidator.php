<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class TwoPerformanceEventsPerDayValidator
 * @package AppBundle\Validator
 */
class TwoPerformanceEventsPerDayValidator extends ConstraintValidator
{
    const MAX_PERFORMANCE_EVENTS_PER_ONE_DAY = 2;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityRepository $repository, TranslatorInterface $translator)
    {
        $this->repository = $repository;
        $this->translator = $translator;
    }

    /**
     * @param \AppBundle\Entity\PerformanceEvent $object
     * @param Constraint                         $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if (false === is_object($object->getDateTime())) { 
            $this->context->addViolationAt(
                'dateTime',
                $this->translator->trans($constraint->performance_must_have_a_date)
            );

            return;
        }

        $from = clone $object->getDateTime();
        $from->setTime(00, 00, 00);

        $to = clone $object->getDateTime();
        $to->setTime(23, 59, 59);

        $countPerformanceEventsPerDate = count($this->repository->findByDateRangeAndSlug($from, $to));

        if ($countPerformanceEventsPerDate >= self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY) {
            $this->context->addViolationAt(
                'dateTime',
                $this->translator->trans($constraint->max_performances_per_day, ['%count%' => self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY])
            );
        }
    }
}
