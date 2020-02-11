<?php

namespace App\Tests\Unit\Validator;

use App\Entity\Media;
use App\Entity\Performance;
use App\Validator\MinSizeSliderImage;
use PHPUnit\Framework\TestCase;
use App\Validator\MinSizeSliderImageValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

class MinSizeSliderImageValidatorTest extends TestCase
{
    public function testNullSliderIsNotValid()
    {
        $performance = new Performance();
        $validator = new MinSizeSliderImageValidator();
        $this->assertEquals(false, $validator->isValid($performance));
    }

    /**
     * @dataProvider isValidProvider
     */
    public function testViolationIfNotValid(int $width, int $height, bool $isValid)
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

        $context->expects($isValid ? $this->never() : $this->once())
            ->method('buildViolation')
            ->willReturn($violationBuilder);

        $media = new Media();
        $media->setWidth($width);
        $media->setHeight($height);

        $performance = new Performance();
        $performance->setSliderImage($media);

        $validator = new MinSizeSliderImageValidator();
        $validator->initialize($context);
        $validator->validate($performance, new MinSizeSliderImage());
    }

    /**
     * @dataProvider isValidProvider
     */
    public function testIsValid(int $width, int $height, bool $isValid)
    {
        $media = new Media();
        $media->setWidth($width);
        $media->setHeight($height);

        $performance = new Performance();
        $performance->setSliderImage($media);

        $validator = new MinSizeSliderImageValidator();
        $this->assertEquals($isValid, $validator->isValid($performance));
    }

    public function isValidProvider()
    {
        return [
            ['width' => 500, 'height' => 400, false],
            ['width' => 500, 'height' => 500, false],
            ['width' => 500, 'height' => 600, false],
            ['width' => 1000, 'height' => 400, false],
            ['width' => 1100, 'height' => 400, false],
            ['width' => 1000, 'height' => 500, true],
            ['width' => 1100, 'height' => 600, true],
        ];
    }
}
