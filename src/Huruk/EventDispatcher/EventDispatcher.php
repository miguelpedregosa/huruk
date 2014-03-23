<?php
/**
 *
 * User: migue
 * Date: 25/11/13
 * Time: 21:42
 */

namespace Huruk\EventDispatcher;

use Huruk\Util\Singleton;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

/**
 * Class EventDispatcher
 * @package Huruk\EventDispatcher
 */
class EventDispatcher extends SymfonyEventDispatcher
{
    use Singleton;

    /**
     * @param $eventName
     * @param $listener
     * @param int $priority
     */
    public static function listen($eventName, $listener, $priority = 0)
    {
        self::on($eventName, $listener, $priority);
    }

    /**
     * @param $eventName
     * @param $listener
     * @param int $priority
     */
    public static function on($eventName, $listener, $priority = 0)
    {
        /** @var EventDispatcher $instance */
        $instance = self::getInstance();
        $instance->addListener($eventName, $listener, $priority);
    }

    /**
     * @param $event_name
     * @param Event $event
     * @return \Symfony\Component\EventDispatcher\Event
     */
    public static function dispatchEvent($event_name, Event $event = null)
    {
        if (is_null($event)) {
            $event = Event::make();
        }
        /** @var EventDispatcher $instance */
        $instance = self::getInstance();
        return $instance->dispatch($event_name, $event);
    }
}
