<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 14:02
 */

namespace Huruk\Application;


use Huruk\EventDispatcher\Event;

class Application
{
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher';
    const LOGGER_SERVICE = 'logger';

    private static $applicationServices = null;

    /**
     * @param $serviceName
     * @param callable $service
     */
    public static function registerService($serviceName, \Closure $service)
    {
        self::getApplicationServices()->registerService($serviceName, $service);
    }

    /**
     * @return ApplicationServices|null
     */
    private static function getApplicationServices()
    {
        if (is_null(self::$applicationServices)) {
            self::$applicationServices = new ApplicationServices();
            static::initializeServices();
        }
        return self::$applicationServices;
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
}
