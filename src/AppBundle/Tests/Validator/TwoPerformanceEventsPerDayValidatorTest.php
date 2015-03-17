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
     * @dataProvider ValidateDataProvider
     */
    public function testValidData($date, $newPerformanceEvent, $performanceEvent1, $performanceEvent2)
    {
        $newPerformanceEvent->setDateTime($date);
        $performanceEvent1->setDateTime($date);
        $performanceEvent2->setDateTime($date);

        $repository = $this->getMockBuilder('AppBundle\Repository\PerformanceEventRepository')->disableOriginalConstructor()->getMock();

        $repository
            ->method('findByDateRangeAndSlug')
            ->will($this->returnValue([$performanceEvent1, $performanceEvent2]))
        ;

        $validator = new TwoPerformanceEventsPerDayValidator($repository, $this->translator);
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
     * @dataProvider ValidateDataProvider
     */
    public function testinValidData($date, $newPerformanceEvent, $performanceEvent1)
    {
        $newPerformanceEvent->setDateTime($date);
        $performanceEvent1->setDateTime($date);

        $repository = $this->getMockBuilder('AppBundle\Repository\PerformanceEventRepository')->disableOriginalConstructor()->getMock();

        $repository
            ->method('findByDateRangeAndSlug')
            ->will($this->returnValue([$performanceEvent1]))
        ;

        $validator = new TwoPerformanceEventsPerDayValidator($repository, $this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->exactly(0))
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->message, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]))
        ;

        $validator->validate($newPerformanceEvent, $this->constraint);
    }

    public function ValidateDataProvider()
    {
        return array(
            array(new \DateTime('27-12-1983 6:00'), new PerformanceEvent(), new PerformanceEvent(), new PerformanceEvent()), );
    }

    public function tearDown()
    {
        $this->constraint = null;
        $this->context = null;
        $this->translator = null;
    }
}
