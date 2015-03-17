<?php

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
     * @dataProvider InvalidDataProvider
     */
    public function testInvalidData($InvalidDataProvider)
    {
        $newPerformanceEvent = new PerformanceEvent();
        $newPerformanceEvent->setDateTime(new \DateTime('27-12-1983 06:00'));

        $validator = new TwoPerformanceEventsPerDayValidator($this->getPerformanceEventRepositoryMock($InvalidDataProvider), $this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->once())
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->message, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]))
        ;

        $validator->validate($newPerformanceEvent, $this->constraint);
    }

    /**
     * @dataProvider ValidDataProvider
     */
    public function testValidData($ValidDataProvider)
    {
        $newPerformanceEvent = new PerformanceEvent();
        $newPerformanceEvent->setDateTime(new \DateTime('27-12-1983 06:00'));

        $validator = new TwoPerformanceEventsPerDayValidator($this->getPerformanceEventRepositoryMock($ValidDataProvider), $this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->exactly(0))
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->message, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]))
        ;

        $validator->validate($newPerformanceEvent, $this->constraint);
    }

    public function InvalidDataProvider()
    {
        return [
            [[new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
            [[new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()]],
        ];
    }

    public function ValidDataProvider()
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
