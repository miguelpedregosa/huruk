<?php
/**
 * User: migue
 * Date: 6/04/14
 * Time: 14:26
 */

namespace Huruk\Services;


use Huruk\EventDispatcher\EventDispatcher;

class ServicesFactory
{
    const SERVICE_EVENT_DISPATCHER = 'event_dispatcher';
    private static $closures = array();
    private static $services = array();

    /**
     * @param $name
     * @param callable $service
     */
    public static function registerService($name, \Closure $service)
    {
        self::$closures[$name] = $service;
        if (isset(self::$services[$name])) {
            self::$services[$name] = null;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function getService($name)
    {
        if (!isset(self::$services[$name])) {
            if (isset(self::$closures[$name]) && is_callable(self::$closures[$name])) {
                self::$services[$name] = call_user_func(self::$closures[$name]);
            }
        }
        return self::$services[$name];
    }


    /**
     * @return EventDispatcher
     */
    public static function getEventDispatcherService()
    {
        if (!self::getService(self::SERVICE_EVENT_DISPATCHER)) {
            self::registerService(
                self::SERVICE_EVENT_DISPATCHER,
                function () {
                    return new EventDispatcher();
                }
            );
        }
        return self::getService(self::SERVICE_EVENT_DISPATCHER);
    }
}
