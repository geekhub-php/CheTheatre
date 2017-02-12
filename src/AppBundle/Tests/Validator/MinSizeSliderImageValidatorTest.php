<?php

namespace AppBundle\Tests\Validator;

use Symfony\Component\CssSelector\XPath\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use AppBundle\Validator\MinSizeSliderImageValidator;
use AppBundle\Validator\MinSizeSliderImage;
use Symfony\Component\Validator\Context\ExecutionContext;

class MinSizeSliderImageValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var MinSizeSliderImage */
    private $constraint;

    /** @var ExecutionContext */
    private $context;

    /** @var TranslatorInterface */
    private $translator;

    public function setUp()
    {
        $this->constraint = new MinSizeSliderImage();
        $this->context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')
            ->disableOriginalConstructor()->getMock();
        $this->translator = $this->createMock('Symfony\Component\Translation\TranslatorInterface');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidData(array $performanceSliderImageSize)
    {
        $newPerformance = $this->getPerformanceEntityMock($performanceSliderImageSize);

        $validator = new MinSizeSliderImageValidator($this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->once())
            ->method('addViolationAt')
            ->with('sliderImage', $this->translator->trans(
                $this->constraint->message,
                [
                    '%height%' => MinSizeSliderImageValidator::MIN_HEIGHT,
                    '%width%' => MinSizeSliderImageValidator::MIN_WIDTH
                ]
            ));

        $validator->validate($newPerformance, $this->constraint);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testValidData(array $performanceSliderImageSize)
    {
        $newPerformance = $this->getPerformanceEntityMock($performanceSliderImageSize);

        $validator = new MinSizeSliderImageValidator($this->translator);
        $validator->initialize($this->context);

        $this
            ->context
            ->expects($this->exactly(0))
            ->method('addViolationAt')
            ->with('sliderImage', $this->translator->trans(
                $this->constraint->message,
                [
                    '%height%' => MinSizeSliderImageValidator::MIN_HEIGHT,
                    '%width%' => MinSizeSliderImageValidator::MIN_WIDTH
                ]
            ));

        $validator->validate($newPerformance, $this->constraint);
    }

    public function invalidDataProvider()
    {
        return [
            [['width' => 500, 'height' => 400]],
            [['width' => 500, 'height' => 500]],
            [['width' => 500, 'height' => 600]],
            [['width' => 1000, 'height' => 400]],
            [['width' => 1100, 'height' => 400]],
        ];
    }

    public function validDataProvider()
    {
        return [
            [['width' => 1000, 'height' => 500]],
            [['width' => 1100, 'height' => 600]],
        ];
    }

    public function getPerformanceEntityMock(array $performanceSliderImageSize)
    {
        $sliderImage = $this->getMockBuilder('Application\Sonata\MediaBundle\Entity\Media')
            ->disableOriginalConstructor()
            ->getMock();

        $sliderImage
            ->method('getWidth')
            ->will($this->returnValue($performanceSliderImageSize['width']))
        ;

        $sliderImage
            ->method('getHeight')
            ->will($this->returnValue($performanceSliderImageSize['height']))
        ;

        $performance = $this->getMockBuilder('AppBundle\Entity\Performance')->disableOriginalConstructor()->getMock();

        $performance
            ->method('getSliderImage')
            ->will($this->returnValue($sliderImage))
        ;

        return $performance;
    }

    public function tearDown()
    {
        $this->constraint = null;
        $this->context = null;
        $this->translator = null;
    }
}
