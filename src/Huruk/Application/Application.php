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
use Huruk\Services\ServicesFactory;

class Application
{
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher_service';

    /**
     * @return EventDispatcher
     */
    public static function getEventDispatcherService()
    {
        if (!ServicesFactory::getService(self::EVENT_DISPATCHER_SERVICE)) {
            ServicesFactory::registerService(
                self::EVENT_DISPATCHER_SERVICE,
                function () {
                    return new EventDispatcher();
                }
            );
        }
        return ServicesFactory::getService(self::EVENT_DISPATCHER_SERVICE);
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
     * @param $eventName
     * @param Event $event
     */
    public static function trigger($eventName, Event $event = null)
    {
        self::getEventDispatcherService()->trigger($eventName, $event);
    }
}
