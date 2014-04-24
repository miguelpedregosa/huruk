<?php
namespace Huruk\Routing;

use Huruk\Application\Application;
use Huruk\EventDispatcher\Event;
use Huruk\Exception\PageNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    const EVENT_ROUTE_DONT_MATCH = 'event.route.dont_match';

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
     * @param $pathInfo
     * @return RouteInfo
     * @throws \Huruk\Exception\PageNotFoundException
     */
    public function matchUrl($pathInfo)
    {
        try {
            $routeParams = $this->getRouter()->match($pathInfo);
            $routeInfo = new RouteInfo($routeParams);
        } catch (\Exception $e) {
            Application::trigger(self::EVENT_ROUTE_DONT_MATCH, new Event(array($pathInfo)));
            throw new PageNotFoundException('Resource not found!!');
        }
        return $routeInfo;
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

            $cache_dir = '/tmp/huruk/router_cache';

            $logger = $this->getLogger();
            if ($logger instanceof LoggerInterface) {
                $logger->debug('');
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
                        'matcher_cache_class' => 'ProjectUrlMatcher' . $routes_md5,
                        'generator_cache_class' => 'ProjectUrlGenerator' . $routes_md5
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
