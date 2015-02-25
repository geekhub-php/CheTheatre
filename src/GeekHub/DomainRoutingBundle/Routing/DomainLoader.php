<?php

namespace GeekHub\DomainRoutingBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use Psr\Log\LoggerInterface;

class DomainLoader extends DelegatingLoader implements LoaderInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var  string */
    protected $kernelRootDir;

    /** @var  string */
    protected $domainRoutingRelations;

    public function __construct(
        RequestStack $requestStack,
        ControllerNameParser $parser,
        LoggerInterface $logger = null,
        LoaderResolver $routingLoader,
        $kernelRootDir,
        $domainRoutingRelations
    )
    {
        $this->requestStack = $requestStack;
        $this->kernelRootDir = $kernelRootDir;
        $this->domainRoutingRelations = $domainRoutingRelations;

        parent::__construct($parser, $logger, $routingLoader);
    }

    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $domainSpecificRoutes = new RouteCollection();

        if (!$request = $this->requestStack->getMasterRequest()) {
            return $domainSpecificRoutes;
        }

        $domain = $request->getHttpHost();

        if (!array_key_exists($domain, $this->domainRoutingRelations)) {
            return $domainSpecificRoutes;
        }

        foreach ($this->domainRoutingRelations[$domain] as $routeResource) {
            $collection = parent::load($this->kernelRootDir . '/' . $routeResource);

            foreach ($collection as $name => $route) {
                $domainSpecificRoutes->add($name, $route);
            }
        }

        return $domainSpecificRoutes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'domain_resolver' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
        // TODO: Implement getResolver() method.
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // TODO: Implement setResolver() method.
    }

    public function setRoutingLoader(LoaderResolver $routingLoader)
    {
        $this->routingLoader = $routingLoader;
    }
}
