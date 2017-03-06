<?php
namespace AppBundle\Tests\Controller\SwaggerValidator\Parameters\PathParameter;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\AbstractParameterValidator;
use Epfremme\Swagger\Entity\Parameters\AbstractParameter;
use Epfremme\Swagger\Entity\Parameters\PathParameter\IntegerType;

class IntegerPathParameterValidator extends AbstractParameterValidator
{
    /**
     * @param AbstractParameter $parameterDoc
     * @param $parameterRequest
     */
    public function validate(AbstractParameter $parameterDoc, $parameterRequest)
    {
        self::assertInstanceOf(IntegerType::class, $parameterDoc);

        /**
         * @var IntegerType $parameterDoc
         */
        if ($parameterRequest === null) {
            self::assertFalse($parameterDoc->isRequired());
            self::assertNotFalse($parameterDoc->getDefault());
            $parameterRequest = $parameterDoc->getDefault();
        }
        self::assertRegExp('/^\d+$/', $parameterRequest);
    }
}
