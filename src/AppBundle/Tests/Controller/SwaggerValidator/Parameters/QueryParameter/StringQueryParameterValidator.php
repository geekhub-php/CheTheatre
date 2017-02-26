<?php

namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters\QueryParameter;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\AbstractParameterValidator;
use Epfremme\Swagger\Entity\Parameters\AbstractParameter;
use Epfremme\Swagger\Entity\Parameters\QueryParameter\StringType;

class StringQueryParameterValidator extends AbstractParameterValidator
{
    /**
     * @param AbstractParameter $parameterDoc
     * @param string $parameterRequest
     */
    public function validate(AbstractParameter $parameterDoc, $parameterRequest){

        self::assertInstanceOf(StringType::class, $parameterDoc);

        /**
         * @var StringType $parameterDoc
         */
        if ($parameterRequest === null) {
            self::assertFalse($parameterDoc->isRequired());
            $parameterRequest = $parameterDoc->getDefault();
            self::assertNotNull($parameterRequest);
        }

        if ($parameterDoc->getPattern()) {
            self::assertRegExp($parameterDoc->getPattern(), $parameterRequest);
        }

        if ($parameterDoc->getEnum()) {
            self::assertContains($parameterRequest, $parameterDoc->getEnum());
        }
    }
}
