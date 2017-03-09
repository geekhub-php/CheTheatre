<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters\HeaderParameter;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\AbstractParameterValidator;
use Epfremme\Swagger\Entity\Parameters\AbstractParameter;
use Epfremme\Swagger\Entity\Parameters\HeaderParameter\StringType;

class StringHeaderParameterValidator extends AbstractParameterValidator
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
        }

        self::assertInternalType(StringType::STRING_TYPE, $parameterRequest);

        if ($parameterDoc->getPattern()) {
            self::assertRegExp($parameterDoc->getPattern(), $parameterRequest);
        }
    }
}
