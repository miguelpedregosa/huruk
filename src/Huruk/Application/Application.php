<?php
namespace Huruk\Application;


use Huruk\Dispatcher\Dispatcher;
use Huruk\Dispatcher\Response;
use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Exception\PageNotFoundException;
use Huruk\Layout\Html5Layout;
use Huruk\Routing\Router;
use Huruk\Services\ServicesContainer;
use Huruk\Util\Charset;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

abstract class Application
{
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher';
    const LOGGER_SERVICE = 'logger';

    private static $servicesContainer = null;

    private function __construct()
    {
    }

    /**
     * @param $serviceName
     * @param callable $service
     */
    public static function registerService($serviceName, \Closure $service)
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
     * @return mixed
     */
    public static function getService($serviceName)
    {
        return self::getApplicationServices()->getService($serviceName);
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
            $logger = self::getService(self::LOGGER_SERVICE);
            $logger = ($logger instanceof LoggerInterface) ? $logger : null;

            //Router
            $collection = ($collection) ? $collection : self::getRouteCollection();
            $router = new Router();
            $router->setRouteCollection($collection)
                ->setRequestContext($requestContext);
            if ($logger) {
                $router->setLogger($logger);
            }

            //RouteInfo y Dispatch
            $route_info = $router->matchUrl($requestContext->getPathInfo());
            $dispatcher->dispatch($route_info);

        } catch (PageNotFoundException $exception) {
            self::handlePageNotFound($dispatcher, $exception);
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

    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
