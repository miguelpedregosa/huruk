<?php
namespace Huruk\Application;


use Huruk\Dispatcher\ClosureStorage;
use Huruk\Dispatcher\Dispatcher;
use Huruk\Dispatcher\Response;
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
     * @param Request $request
     */
    public static function get($path, \Closure $function, Request $request = null)
    {
        $closureStorage = ClosureStorage::getInstance();
        $routeName = self::getRouteNameFromPath($path, 'GET');
        $closureStorage[$routeName] = $function;
        $collection = new RouteCollection();
        $route = new Route($path, array(RouteInfo::CLOSURE => true));
        $route->setMethods('GET');
        $collection->add($routeName, $route);
        self::run($collection, $request);
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
     * @param RouteCollection $collection
     * @param Request $request
     * @throws \Exception
     */
    public static function run(RouteCollection $collection = null, Request $request = null)
    {
        //Dispatcher
        $dispatcher = new Dispatcher();

        try {
            //Request context
            if (!$request instanceof Request) {
                $request = Request::createFromGlobals();
            }
            $dispatcher->setRequest($request);
            $requestContext = new RequestContext();
            $requestContext->fromRequest($request);

            //Logger
            $logger = self::getService(Application::LOGGER_SERVICE);
            $logger = ($logger instanceof LoggerInterface) ? $logger : null;

            //Router
            $collection = ($collection) ? $collection : static::getRouteCollection();
            /** @var Router $router */
            $router = self::getService(Application::ROUTER_SERVICE);
            $router->setRouteCollection($collection)
                ->setRequestContext($requestContext);
            if ($logger) {
                $router->setLogger($logger);
            }

            //RouteInfo y Dispatch
            $routeInfo = $router->matchUrl($requestContext->getPathInfo());
            $dispatcher->dispatch($routeInfo);

        } catch (PageNotFoundException $exception) {
            static::handlePageNotFound($dispatcher, $exception);
        }
    }

    /**
     * @return RouteCollection
     */
    protected static function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @param Dispatcher $dispatcher
     * @param PageNotFoundException $exception
     */
    protected static function handlePageNotFound(Dispatcher $dispatcher, PageNotFoundException $exception)
    {
        $html_layout = new Html5Layout();
        $html_layout->setTitle('Huruk µFramework - Not Found')
            ->setApplicationName('Huruk µFramework')
            ->setCharset(Charset::CHARSET_UTF8);
        $html = $html_layout->render('<h1>Not found</h1><code>' . $exception->getMessage() . '</code>');
        $dispatcher->sendResponse(Response::make($html, 404));
    }

    /**
     * @param $path
     * @param callable $function
     * @param Request $request
     */
    public static function post($path, \Closure $function, Request $request = null)
    {
        $closureStorage = ClosureStorage::getInstance();
        $routeName = self::getRouteNameFromPath($path, 'POST');
        $closureStorage[$routeName] = $function;
        $collection = new RouteCollection();
        $route = new Route($path, array(RouteInfo::CLOSURE => true));
        $route->setMethods('POST');
        $collection->add($routeName, $route);
        self::run($collection, $request);
    }

    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
