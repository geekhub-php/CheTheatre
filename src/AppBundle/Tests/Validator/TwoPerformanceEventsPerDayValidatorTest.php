<?php

namespace AppBundle\Tests\Validator;

use Symfony\Component\Validator\Constraint;
use AppBundle\Validator\TwoPerformanceEventsPerDayValidator;
use AppBundle\Validator\TwoPerformanceEventsPerDay;
use AppBundle\Entity\PerformanceEvent;

class TwoPerformanceEventsPerDayValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $constraint;
    private $context;
    private $translator;

    public function setUp()
    {
        $this->constraint = new TwoPerformanceEventsPerDay();
        $this->context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')->disableOriginalConstructor()->getMock();
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidData(array $perfomanceEvents)
    {
        $newPerformanceEvent = new PerformanceEvent();
        $newPerformanceEvent->setDateTime(new \DateTime());

        $validator = new TwoPerformanceEventsPerDayValidator($this->getPerformanceEventRepositoryMock($perfomanceEvents), $this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->once())
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->max_performances_per_day, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]))
        ;

        $validator->validate($newPerformanceEvent, $this->constraint);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testValidData(array $perfomanceEvents)
    {
        $newPerformanceEvent = new PerformanceEvent();
        $newPerformanceEvent->setDateTime(new \DateTime());

        $validator = new TwoPerformanceEventsPerDayValidator($this->getPerformanceEventRepositoryMock($perfomanceEvents), $this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->exactly(0))
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->max_performances_per_day, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]))
        ;

        $validator->validate($newPerformanceEvent, $this->constraint);
    }

    public function invalidDataProvider()
    {
        return [
            [[new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
        ];
    }

    public function validDataProvider()
    {
        return [
            [[]],
            [[new PerformanceEvent()]],
        ];
    }

    public function getPerformanceEventRepositoryMock(array $perfomanceEvents)
    {
        $repository = $this->getMockBuilder('AppBundle\Repository\PerformanceEventRepository')->disableOriginalConstructor()->getMock();

        $repository
            ->method('findByDateRangeAndSlug')
            ->will($this->returnValue($perfomanceEvents))
        ;

        return $repository;
    }

    public function tearDown()
    {
        $this->constraint = null;
        $this->context = null;
        $this->translator = null;
    }
}
