<?php

namespace AppBundle\Tests\Controller\SwaggerValidator;

use AppBundle\Tests\Controller\SwaggerValidator\Parameters\ParameterValidatorFactory;
use AppBundle\Tests\Controller\SwaggerValidator\Shemas\SchemaValidatorFactory;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Epfremme\Swagger\Entity\Operation;
use Epfremme\Swagger\Entity\Path;
use Epfremme\Swagger\Entity\Response as SwaggerResponse;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;
use Epfremme\Swagger\Entity\Swagger;
use Epfremme\Swagger\Factory\SwaggerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SwaggerSpecValidator extends \PHPUnit_Framework_Assert
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
     * @param Request $request
     * @param Response $response
     * @throws SwaggerSchemaException
     */
    public function assertResource(string $operationId, Request $request, Response $response)
    {
        try {
            $path = $this->getPath($operationId);
            $method = strtolower($request->getMethod());
            $this->assertAllowedHttpMethod($method, $path);

            /** @var Operation $operation */
            $operation = $path->getOperations()->get($method);
            $statusCode = $response->getStatusCode();
            $this->assertAllowedStatusCode($statusCode, $operation);

            $this->assertAllowedContentType($response, $operation);
            $this->assertRequest($request, $operation);

            /** @var SwaggerResponse $documentedResponse */
            $documentedResponse = $operation->getResponses()[$statusCode];
            $this->assertResponse($response, $documentedResponse);
        } catch (\Exception $e) {
            throw new SwaggerSchemaException(
                sprintf(
                    "Error while assert Schema for Request:\n%s\nAnd Response:\n%s",
                    $request->__toString(),
                    $response->__toString()
                ),
                0,
                $e
            );
        } catch (\TypeError $e) {
            throw new SwaggerSchemaException(
                sprintf(
                    "Error while assert Schema for Request:\n%s\nAnd Response:\n%s",
                    $request->__toString(),
                    $response->__toString()
                ),
                0,
                $e
            );
        }
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

    /**
     * @param Response $response
     * @param SwaggerResponse $documentedResponse
     */
    private function assertResponse(Response $response, SwaggerResponse $documentedResponse)
    {
        if (null === $documentedResponse->getSchema()) {
            // todo: run application in prod mode to suppress debug in body
//            self::assertNull($response->getContent());
            return;
        }

        $this->assertSchema($response, $documentedResponse->getSchema());
    }

    /**
     * @param Response $response
     * @param SchemaInterface $schema
     */
    private function assertSchema(Response $response, SchemaInterface $schema)
    {
        $json = $response->getContent();
        $actualContent = json_decode($json);

        $validator = $this->getSchemaValidatorFactory()->getValidatorByType($schema->getType());
        $validator->validate($schema, $actualContent);
    }

    /**
     * @param Request $request
     * @param Operation $operation
     */
    private function assertRequest(Request $request, Operation $operation)
    {
        foreach ($operation->getParameters() as $parameterDoc) {
            switch ($request->getMethod()) {
                case 'GET':
                    $parameterRequest = $request->get($parameterDoc->getName());
                    break;
                case 'POST':
                    $parameterRequest = $request->request->get($parameterDoc->getName());
                    break;
                default:
                    throw new \Exception('Unsupported method');
            }
            $factory = $this->getParameterValidatorFactory();
            $validator = $factory->getValidatorByType($parameterDoc->getIn(), $parameterDoc->getType());
            $validator->validate($parameterDoc, $parameterRequest);
        }
    }

    /**
     * @param Response $response
     * @param Operation $operation
     */
    private function assertAllowedContentType(Response $response, Operation $operation)
    {
        $actualContentType = $response->headers->get('content-type');
        $expectedContentTypes = $operation->getProduces();
        self::assertContains(
            $actualContentType,
            $expectedContentTypes,
            sprintf('Allowed "%s" content types but got "%s"', implode(', ', $expectedContentTypes), $actualContentType)
        );
    }

    /**
     * @param string $method
     * @param Operation $operation
     */
    private function assertAllowedStatusCode(string $method, Operation $operation)
    {
        $allowedStatusCodes = $operation->getResponses()->getKeys();
        self::assertContains(
            $method,
            $allowedStatusCodes,
            sprintf(
                'Allowed "%s" status codes but got "%s"',
                implode(', ', $allowedStatusCodes),
                $method
            )
        );
    }

    /**
     * @param string $method
     * @param Path $path
     */
    private function assertAllowedHttpMethod(string $method, Path $path)
    {
        $allowedHttpMethods = $path->getOperations()->getKeys();
        self::assertContains(
            $method,
            $allowedHttpMethods,
            sprintf(
                'Allowed "%s" operations but got "%s"',
                implode(', ', $allowedHttpMethods),
                $method
            )
        );
    }
}
