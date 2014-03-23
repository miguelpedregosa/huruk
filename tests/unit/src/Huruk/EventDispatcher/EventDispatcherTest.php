<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 11:08
 */

namespace unit\src\Huruk\EventDispatcher;


use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;

/**
 * Class EventDispatcherTest
 * @package unit\src\Huruk\EventDispatcher
 * @coversDefaultClass Huruk\EventDispatcher\EventDispatcher
 */
class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    private $counter = 0;

    /**
     * @covers ::on
     */
    public function testOn()
    {
        $event_dispatcher = new EventDispatcher();
        $function = function () {
            //Does nothing
        };
        $event_dispatcher->on(
            'foo',
            $function
        );
        $this->assertCount(1, $event_dispatcher->getListeners('foo'));
    }

    /**
     * @covers ::listen
     * @covers ::on
     * @covers ::getListeners
     */
    public function testListen()
    {
        $event_dispatcher = new EventDispatcher();
        $function = function () {
            //Does nothing
        };
        $event_dispatcher->listen(
            'foo',
            $function
        );
        $this->assertCount(1, $event_dispatcher->getListeners('foo'));
    }

    /**
     * @covers ::dispatch
     * @covers ::listen
     * @covers ::on
     */
    public function testDispatch()
    {
        $event_dispatcher = new EventDispatcher();
        $function = function () {
            $this->counter++;
        };
        $event_dispatcher->listen(
            'foo',
            $function
        );

        //Trigger
        $event_dispatcher->dispatch('foo');
        $this->assertEquals(1, $this->counter);
    }

    /**
     * @covers ::dispatchEvent
     * @covers ::dispatch
     * @covers ::listen
     * @covers ::on
     */
    public function testDispatchEvent()
    {
        $event_dispatcher = new EventDispatcher();
        $function = function (Event $event) {
            $this->assertInstanceOf('Huruk\EventDispatcher\Event', $event);
            if (isset($event['foo'])) {
                $this->assertEquals('bar', $event['foo']);
            }
        };
        $event_dispatcher->listen(
            'foo',
            $function
        );

        //Trigger
        $event_dispatcher->dispatchEvent('foo');
        $event_dispatcher->dispatchEvent('foo', Event::make(array('foo' => 'bar')));
    }
}
