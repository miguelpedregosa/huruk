<?php
namespace Huruk\Application;


use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Services\ServicesContainer;

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

    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
