<?php

namespace Huruk\Routing;

use Huruk\Exception\PageNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    /** @var  \Symfony\Component\Routing\Router */
    private $router;
    private $routeCollection;
    private $requestContext;
    private $logger;


    /**
     * @param RouteCollection $collection
     * @param RequestContext $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        RouteCollection $collection = null,
        RequestContext $context = null,
        LoggerInterface $logger = null
    ) {
        $this->routeCollection = $collection;
        $this->requestContext = $context;
        $this->logger = $logger;
    }

    /**
     * @param $path_info
     * @return array
     * @throws \Huruk\Exception\PageNotFoundException
     */
    public function matchUrl($path_info)
    {
        try {
            $route_params = $this->getRouter()->match($path_info);
            $route_info = new RouteInfo($route_params);
        } catch (\Exception $e) {
            throw new PageNotFoundException('Resource not found!!');
        }
        return $route_info;
    }

    /**
     * @return \Symfony\Component\Routing\Router
     * @throws \Exception
     */
    private function getRouter()
    {
        if (!$this->router instanceof \Symfony\Component\Routing\Router) {

            if (!$this->getRouteCollection() instanceof RouteCollection) {
                throw new \Exception('No RouteCollection');
            }

            if (!$this->getRequestContext() instanceof RequestContext) {
                throw new \Exception('No RequestContext');
            }

            $routes_md5 = md5(serialize($this->getRouteCollection()));
            $closure_loader = new ClosureLoader();

            $cache_dir = '/tmp/huruk/route_cache_' . $routes_md5;

            $logger = $this->getLogger();
            if ($logger instanceof LoggerInterface) {
                $logger->debug('Router cache dir: ' . $cache_dir);
            } else {
                $logger = null;
            }

            $this->router =
                new \Symfony\Component\Routing\Router(
                    $closure_loader,
                    function () {
                        return $this->getRouteCollection();
                    },
                    array(
                        'cache_dir' => $cache_dir,
                        'debug' => false,
                    ),
                    $this->getRequestContext(),
                    $logger
                );
        }

        return $this->router;
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    /**
     * @param RouteCollection $collection
     * @return Router
     */
    public function setRouteCollection(RouteCollection $collection)
    {
        $this->routeCollection = $collection;
        return $this;
    }

    /**
     * @return RequestContext
     */
    public function getRequestContext()
    {
        return $this->requestContext;
    }

    /**
     * @param RequestContext $context
     * @return Router
     */
    public function setRequestContext(RequestContext $context)
    {
        $this->requestContext = $context;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Router
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param $name
     * @param array $parameters
     * @param bool $referenceType
     * @return null|string
     * @throws \Exception
     */
    public function generateUrl(
        $name,
        $parameters = array(),
        $referenceType = UrlGenerator::ABSOLUTE_PATH
    ) {
        return $this->getRouter()->generate($name, $parameters, $referenceType);
    }
}
