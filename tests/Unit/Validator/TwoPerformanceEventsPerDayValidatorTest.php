<?php

namespace App\Tests\Unit\Validator;

use App\Repository\PerformanceEventRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use App\Validator\TwoPerformanceEventsPerDayValidator;
use App\Validator\TwoPerformanceEventsPerDay;
use App\Entity\PerformanceEvent;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

class TwoPerformanceEventsPerDayValidatorTest extends TestCase
{
    /**
     * @dataProvider validateProvider
     */
    public function testValidate(PerformanceEvent $event, int $num, bool $isValid, string $constraint = null)
    {
        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock();
        $violationBuilder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['atPath', 'setParameter', 'addViolation'])
            ->getMock();
        $violationBuilder->method('atPath')->willReturn($violationBuilder);
        $violationBuilder->method('setParameter')->willReturn($violationBuilder);

        $mocker = $context->expects($isValid ? $this->never() : $this->once())
            ->method('buildViolation');
        if (!$isValid) {
            $mocker->with($this->stringContains($constraint));
        }
        $mocker->willReturn($violationBuilder);
        $repository = $this->getPerformanceEventRepositoryMock($num);
        $validator = new TwoPerformanceEventsPerDayValidator($repository);
        $validator->initialize($context);
        $validator->validate($event, new TwoPerformanceEventsPerDay());
    }

    public function validateProvider()
    {
        $eventNoDate = new PerformanceEvent();
        $event = (new PerformanceEvent())->setDateTime(new \DateTime());
        $constraint = new TwoPerformanceEventsPerDay();

        return [
            [$eventNoDate, 0, false, $constraint->performance_must_have_a_date],
            [$event, 0, true],
            [$event, 9, true],
            [$event, 10, false, $constraint->max_performances_per_day],
            [$event, 11, false, $constraint->max_performances_per_day],
            [$event, 12, false, $constraint->max_performances_per_day],
            [$event, 13, false, $constraint->max_performances_per_day],
            [$event, 20, false, $constraint->max_performances_per_day],
        ];
    }

    public function getPerformanceEventRepositoryMock(int $num)
    {
        $repository = $this->getMockBuilder(PerformanceEventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository
            ->method('findByDateRangeAndSlug')
            ->will($this->returnValue(array_fill(0, $num, new PerformanceEvent())))
        ;

        return $repository;
    }
}
