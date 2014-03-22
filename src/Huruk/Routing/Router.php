<?php
/**
 *
 * User: migue
 * Date: 24/11/13
 * Time: 16:26
 */

namespace Huruk\Routing;

use Huruk\Application\ApplicationAccess;
use Huruk\Application\ApplicationInterface;
use Huruk\Exception\PageNotFoundException;
use Monolog\Logger;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router implements ApplicationAccess
{
    /** @var  \Symfony\Component\Routing\Router */
    private $router;
    private $application;
    private $logger;
    private $route_collection;
    private $request_context;


    /**
     * @param RouteCollection $collection
     * @param RequestContext $context
     * @param Logger $logger
     */
    public function __construct(RouteCollection $collection, RequestContext $context, Logger $logger)
    {
        $this->route_collection = $collection;
        $this->request_context = $context;
        $this->logger = $logger;
    }


    /**
     * @return \Symfony\Component\Routing\Router
     */
    private function getRouter()
    {
        if (!$this->router instanceof \Symfony\Component\Routing\Router) {

            $closure = function () {
                return $this->route_collection;
            };

            $routes_timestamp = md5(serialize($this->route_collection));
            $closure_loader = new ClosureLoader();

            $cache_dir = '/tmp/huruk/route_cache_' .
                strtolower($this->getApplication()->getName()).'/'.$routes_timestamp;

            $this->getApplication()->log()->addDebug('Router cache dir: '.$cache_dir);

            $router_options = array(
                'cache_dir' => $cache_dir,
                'debug' => false,
            );

            $this->router =
                new \Symfony\Component\Routing\Router(
                    $closure_loader,
                    $closure,
                    $router_options,
                    $this->request_context,
                    $this->logger
                );
        }

        return $this->router;
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
        } catch (\Exception $e) {
            throw new PageNotFoundException('Resource not found!!');
        }
        return $route_params;
    }

    /**
     * @param $name
     * @param array $parameters
     * @param bool $referenceType
     * @return null|string
     * @throws \Exception
     */
    public function generateUrl($name, $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->getRouter()->generate($name, $parameters, $referenceType);
    }

    /**
     * @param ApplicationInterface $app
     * @return void
     */
    public function setApplication(ApplicationInterface $app)
    {
        $this->application = $app;
    }

    /**
     * @return ApplicationInterface
     */
    public function getApplication()
    {
        return $this->application;
    }
}
