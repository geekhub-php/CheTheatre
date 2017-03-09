<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\AbstractParameterValidator;
use Epfremme\Swagger\Entity\Parameters\AbstractParameter;
use Epfremme\Swagger\Entity\Parameters\PathParameter\StringType;

class StringPathParameterValidator extends AbstractParameterValidator
{
    /**
     * @param AbstractParameter $parameterDoc
     * @param string $parameterRequest
     */
    public function validate(AbstractParameter $parameterDoc, $parameterRequest)
    {
        self::assertInstanceOf(StringType::class, $parameterDoc);

        /**
         * @var StringType $parameterDoc
         */
        if ($parameterRequest === null) {
            self::assertFalse($parameterDoc->isRequired());
            self::assertNotFalse($parameterDoc->getDefault());
            $parameterRequest = $parameterDoc->getDefault();
        }

        if ($parameterDoc->getPattern()) {
            self::assertRegExp($parameterDoc->getPattern(), $parameterRequest);
        }

        if ($parameterDoc->getEnum()) {
            self::assertContains($parameterRequest, $parameterDoc->getEnum());
        }
    }
}
