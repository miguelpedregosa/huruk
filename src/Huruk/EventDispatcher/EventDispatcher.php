<?php
/**
 *
 * User: migue
 * Date: 25/11/13
 * Time: 21:42
 */

namespace Huruk\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

/**
 * Class EventDispatcher
 * @package Huruk\EventDispatcher
 */
class EventDispatcher extends SymfonyEventDispatcher
{
    /**
     * @param $eventName
     * @param $listener
     * @param int $priority
     */
    public function listen($eventName, $listener, $priority = 0)
    {
        $this->on($eventName, $listener, $priority);
    }

    /**
     * @param $eventName
     * @param $listener
     * @param int $priority
     */
    public function on($eventName, $listener, $priority = 0)
    {
        $this->addListener($eventName, $listener, $priority);
    }

    /**
     * @param $event_name
     * @param Event $event
     * @return \Symfony\Component\EventDispatcher\Event
     */
    public function dispatchEvent($event_name, Event $event = null)
    {
        if (is_null($event)) {
            $event = Event::make();
        }
        return $this->dispatch($event_name, $event);
    }
}
