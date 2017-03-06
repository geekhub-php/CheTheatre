<?php

namespace AppBundle\Tests\Controller\SwaggerValidator;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\ParameterValidatorFactory;
use AppBundle\Tests\Controller\SwaggerValidator\Shemas\SchemaValidatorFactory;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Epfremme\Swagger\Entity\Path;
use Epfremme\Swagger\Entity\Swagger;
use Epfremme\Swagger\Factory\SwaggerFactory;

abstract class AbstractSwagger extends \PHPUnit_Framework_Assert
{
    /**
     * @var Swagger
     */
    protected $swagger;

    /**
     * @var SchemaValidatorFactory
     */
    protected $schemaValidatorFactory;

    /**
     * @var ParameterValidatorFactory
     */
    protected $parameterValidatorFactory;

    /**
     * AbstractSwagger constructor.
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->paths = new ArrayCollection();

        AnnotationRegistry::registerLoader('class_exists');
        $factory = new SwaggerFactory();
        $this->swagger = $factory->build($source);
        $this->schemaValidatorFactory = new SchemaValidatorFactory();
        $this->parameterValidatorFactory = new ParameterValidatorFactory();
    }

    /**
     * @param string $operationId
     * @return Path
     */
    protected function getPath(string $operationId):Path
    {
        foreach ($this->swagger->getPaths() as $path) {
            foreach ($path->getOperations() as $operation) {
                if ($operationId === $operation->getOperationId()) {
                    return $path;
                }
            }
        }

        self::fail(sprintf(
            'Swagger documentation has no resourse for "%s" operation id',
            $operationId
        ));
    }

    /**
     * @return SchemaValidatorFactory
     */
    protected function getSchemaValidatorFactory(): SchemaValidatorFactory
    {
        return $this->schemaValidatorFactory;
    }

    /**
     * @return ParameterValidatorFactory
     */
    protected function getParameterValidatorFactory(): ParameterValidatorFactory
    {
        return $this->parameterValidatorFactory;
    }
}
