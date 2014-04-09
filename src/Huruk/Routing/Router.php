<?php
/**
 *
 * User: migue
 * Date: 24/11/13
 * Time: 16:26
 */

namespace Huruk\Routing;

use Huruk\Exception\PageNotFoundException;
use Huruk\Services\ServicesFactory;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    /** @var  \Symfony\Component\Routing\Router */
    private $router;
    private $route_collection;
    private $request_context;


    /**
     * @param RouteCollection $collection
     * @param RequestContext $context
     */
    public function __construct(RouteCollection $collection, RequestContext $context)
    {
        $this->route_collection = $collection;
        $this->request_context = $context;
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
     * @return \Symfony\Component\Routing\Router
     */
    private function getRouter()
    {
        if (!$this->router instanceof \Symfony\Component\Routing\Router) {
            $closure = function () {
                return $this->route_collection;
            };

            $routes_md5 = md5(serialize($this->route_collection));
            $closure_loader = new ClosureLoader();

            $cache_dir = '/tmp/huruk/route_cache_' . $routes_md5;
            $logger = ServicesFactory::getLoggerService();
            $logger->addDebug('Router cache dir: ' . $cache_dir);


            $this->router =
                new \Symfony\Component\Routing\Router(
                    $closure_loader,
                    $closure,
                    array(
                        'cache_dir' => $cache_dir,
                        'debug' => false,
                    ),
                    $this->request_context,
                    $logger
                );
        }

        return $this->router;
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
