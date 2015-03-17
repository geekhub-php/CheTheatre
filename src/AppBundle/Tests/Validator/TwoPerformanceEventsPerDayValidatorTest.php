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
    public function testValidate($object, $repository)
    {
        $validator = new TwoPerformanceEventsPerDayValidator($repository, $this->translator);
        $validator->initialize($this->context);

        $this->context->expects($this->once())
            ->method('addViolationAt')
            ->with('dateTime', $this->translator->trans($this->constraint->message, ['%count%' => TwoPerformanceEventsPerDayValidator::MAX_PERFORMANCE_EVENTS_PER_ONE_DAY]));

        $validator->validate($object, $this->constraint);
    }

    public function ValidateDataProvider()
    {
        $object = new PerformanceEvent();
        $object->setDateTime(new \DateTime('27-12-1983 6:00'));

        $objectFromRepository1 = new PerformanceEvent();
        $objectFromRepository1->setDateTime(new \DateTime('27-12-1983 6:00'));

        $objectFromRepository2 = new PerformanceEvent();
        $objectFromRepository2->setDateTime(new \DateTime('27-12-1983 6:00'));

        $repository =
            $this
                ->getMockBuilder('AppBundle\Repository\PerformanceEventRepository')
                ->disableOriginalConstructor()
                ->getMock();

        $repository
            ->method('findByDateRangeAndSlug')
            ->will($this->returnValue([$objectFromRepository1, $objectFromRepository2]))
        ;

        return array(
            array($object, $repository), );
    }

    public function tearDown()
    {
        $this->constraint = null;
        $this->context = null;
        $this->translator = null;
    }
}
