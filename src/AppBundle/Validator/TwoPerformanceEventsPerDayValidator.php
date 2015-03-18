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
        $from = clone $object->getDateTime();
        $from->setTime(00, 00, 00);

        $to = clone $object->getDateTime();
        $to->setTime(23, 59, 59);

        $countPerformanceEventsPerDate = count($this->repository->findByDateRangeAndSlug($from, $to));

        if ($countPerformanceEventsPerDate >= self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY) {
            $this->context->addViolationAt(
                'dateTime',
                $this->translator->trans($constraint->message, ['%count%' => self::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY])
                )
            ;
        }
    }
}
