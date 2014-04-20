<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 14:02
 */

namespace Huruk\Application;


use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;

class Application
{
    private static $applicationServices = null;

    /**
     * @param $serviceName
     * @return mixed
     */
    public static function getService($serviceName)
    {
        return self::getApplicationServices()->getService($serviceName);
    }

    /**
     * @return ApplicationServices|null
     */
    private static function getApplicationServices()
    {
        if (is_null(self::$applicationServices)) {
            self::$applicationServices = new ApplicationServices();
        }
        return self::$applicationServices;
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
     * @param $eventName
     * @param $listener
     * @param int $prioriy
     */
    public static function listen($eventName, $listener, $prioriy = 0)
    {
        self::getEventDispatcherService()->listen($eventName, $listener, $prioriy);
    }

    /**
     * @return EventDispatcher
     */
    public static function getEventDispatcherService()
    {
        return self::getService(ApplicationServices::EVENT_DISPATCHER_SERVICE);
    }

    /**
     * @param $eventName
     * @param Event $event
     */
    public static function trigger($eventName, Event $event = null)
    {
        self::getEventDispatcherService()->trigger($eventName, $event);
    }

//    public static function run()
//    {
//
//    }
//
//    public static function get($route, \Closure $function)
//    {
//
//    }
//
//    public static function post($route, \Closure $function)
//    {
//
//    }
}
