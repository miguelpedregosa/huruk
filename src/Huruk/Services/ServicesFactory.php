<?php
/**
 * User: migue
 * Date: 6/04/14
 * Time: 14:26
 */

namespace Huruk\Services;


use Huruk\EventDispatcher\EventDispatcher;
use Monolog\Handler\NullHandler;
use Monolog\Logger;

class ServicesFactory
{
    const SERVICE_EVENT_DISPATCHER = 'event_dispatcher';
    const SERVICE_LOGGER = 'logger';
    private static $closures = array();
    private static $services = array();

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
     * @return Logger
     */
    public static function getLoggerService()
    {
        if (!self::getService(self::SERVICE_LOGGER)) {
            self::registerService(
                self::SERVICE_LOGGER,
                function () {
                    $logger = new Logger('application_log');
                    $logger->pushHandler(new NullHandler());
                    return $logger;
                }
            );
        }
        return self::getService(self::SERVICE_LOGGER);
    }
}
