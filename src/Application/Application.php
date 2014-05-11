<?php
namespace Huruk\Application;


use Huruk\Dispatcher\ClosureStorage;
use Huruk\Dispatcher\Dispatcher;
use Huruk\Dispatcher\Response;
use Huruk\Dispatcher\ResponseFactory;
use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Exception\PageNotFoundException;
use Huruk\Layout\Html5Layout;
use Huruk\Routing\RouteInfo;
use Huruk\Routing\Router;
use Huruk\Services\ServicesContainer;
use Huruk\Util\Charset;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

abstract class Application
{
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher';
    const LOGGER_SERVICE = 'logger';
    const ROUTER_SERVICE = 'router';

    private static $servicesContainer = null;
    private static $routeCollection = null;

    private function __construct()
    {
    }

    /**
     * @param $serviceName
     * @param callable $service
     */
    final public static function registerService($serviceName, \Closure $service)
    {
        self::getApplicationServices()->registerService($serviceName, $service);
    }

    /**
     * @return ServicesContainer|null
     */
    private static function getApplicationServices()
    {
        if (is_null(self::$servicesContainer)) {
            self::$servicesContainer = new ServicesContainer();

            //Common services
            self::$servicesContainer->registerService(
                Application::EVENT_DISPATCHER_SERVICE,
                function () {
                    return new EventDispatcher();
                }
            );

            self::$servicesContainer->registerService(
                Application::ROUTER_SERVICE,
                function () {
                    return new Router();
                }
            );

            //Application services
            static::initializeServices();
        }
        return self::$servicesContainer;
    }

    protected static function initializeServices()
    {

    }

    /**
     * @param $eventName
     * @param $listener
     * @param int $prioriy
     */
    public static function listen($eventName, $listener, $prioriy = 0)
    {
        self::getService(self::EVENT_DISPATCHER_SERVICE)->listen($eventName, $listener, $prioriy);
    }

    /**
     * @param $serviceName
     * @param bool $shareInstance
     * @return mixed
     */
    final public static function getService($serviceName, $shareInstance = true)
    {
        return self::getApplicationServices()->getService($serviceName, $shareInstance);
    }

    /**
     * @param $eventName
     * @param Event $event
     */
    public static function trigger($eventName, Event $event = null)
    {
        self::getService(self::EVENT_DISPATCHER_SERVICE)->trigger($eventName, $event);
    }

    /**
     * @param $path
     * @param callable $function
     */
    public static function get($path, \Closure $function)
    {
        $closureStorage = ClosureStorage::getInstance();
        $routeName = self::getRouteNameFromPath($path, 'GET');
        $closureStorage[$routeName] = $function;
        $collection = static::getRoutes();
        $route = new Route($path, array(RouteInfo::CLOSURE => true));
        $route->setMethods('GET');
        $collection->add($routeName, $route);
    }

    /**
     * @param $path
     * @param string $method
     * @return string
     */
    private static function getRouteNameFromPath($path, $method = 'GET')
    {
        return str_replace('/', '_', $path) . '_' . strtoupper($method);
    }

    /**
     * @return RouteCollection
     */
    final private static function getRoutes()
    {
        if (!self::$routeCollection) {
            self::$routeCollection = static::getRouteCollection();
        }
        return self::$routeCollection;
    }

    /**
     * @return RouteCollection
     */
    public static function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @param RouteCollection $routes
     */
    public static function setRouteCollection(RouteCollection $routes)
    {
        self::$routeCollection = $routes;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public static function run(Request $request = null)
    {
        try {
            //Request context
            if (!$request instanceof Request) {
                $request = Request::createFromGlobals();
            }
            $requestContext = new RequestContext();
            $requestContext->fromRequest($request);

            //Logger
            $logger = self::getService(Application::LOGGER_SERVICE);

            //Router
            /** @var Router $router */
            $router = self::getService(Application::ROUTER_SERVICE);
            $router->setRouteCollection(static::getRoutes())
                ->setRequestContext($requestContext);
            if ($logger instanceof LoggerInterface) {
                $router->setLogger($logger);
            }

            //Get RouteInfo from path info
            $routeInfo = $router->matchUrl($requestContext->getPathInfo());

            //Get Response objet
            $dispatcher = new Dispatcher();
            $response = $dispatcher->handleRequest($request, $routeInfo);

        } catch (PageNotFoundException $exception) {
            $response = static::handlePageNotFound($exception);
        } catch (\Exception $exception) {
            $response = static::handleException($exception);
        }

        //Deal with it!!
        if (!$response instanceof Response) {
            throw new \Exception();
        }
        $response->send();
    }

    /**
     * @param PageNotFoundException $exception
     * @return \Huruk\Dispatcher\Response
     */
    protected static function handlePageNotFound(PageNotFoundException $exception)
    {
        $htmlLayout = new Html5Layout();
        $htmlLayout->setTitle('Huruk µFramework - Not Found')
            ->setApplicationName('Huruk µFramework')
            ->setCharset(Charset::CHARSET_UTF8);
        $html = $htmlLayout->render('<h1>Not found</h1><code>' . $exception->getMessage() . '</code>');
        return ResponseFactory::make($html, 404);
    }

    /**
     * @param \Exception $exception
     * @return \Huruk\Dispatcher\Response
     */
    protected static function handleException(\Exception $exception)
    {
        $htmlLayout = new Html5Layout();
        $htmlLayout->setTitle('Huruk µFramework - Error')
            ->setApplicationName('Huruk µFramework')
            ->setCharset(Charset::CHARSET_UTF8);
        $html = $htmlLayout->render('<h1>Not found</h1><code>' . $exception->getMessage() . '</code>');
        return ResponseFactory::make($html, 500);
    }

    /**
     * @param $path
     * @param callable $function
     */
    public static function post($path, \Closure $function)
    {
        $closureStorage = ClosureStorage::getInstance();
        $routeName = self::getRouteNameFromPath($path, 'POST');
        $closureStorage[$routeName] = $function;
        $collection = $collection = static::getRoutes();
        $route = new Route($path, array(RouteInfo::CLOSURE => true));
        $route->setMethods('POST');
        $collection->add($routeName, $route);
    }

    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
