<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 10:54
 */

namespace unit\src\Huruk\EventDispatcher;


use Huruk\EventDispatcher\Event;

/**
 * Class EventTest
 * @package unit\src\Huruk\EventDispatcher
 * @coversDefaultClass \Huruk\EventDispatcher\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testDataEvent()
    {
        $data = array('foo' => 'bar');
        $event = new Event($data);
        $this->assertEquals($data, $event->getData());
    }

    public function testSetData()
    {
        $data = array('foo' => 'bar');
        $event = new Event();
        $event->setData($data);
        $this->assertEquals($data, $event->getData());
    }

    public function testFactory()
    {
        $data = array('foo' => 'bar');
        $event = Event::make($data);
        $this->assertEquals($data, $event->getData());
    }

    public function testArrayAccess ()
    {
        $event = new Event();
        $event['foo'] = 'bar';
        $event['xxx'] = 'yyy';

        $this->assertEquals('bar', $event['foo']);
        $this->assertEquals('yyy', $event['xxx']);

        $this->assertNull($event['non_exists']);
        $this->assertTrue(isset($event['foo']));
        unset($event['foo']);
        $this->assertFalse(isset($event['foo']));

    }
}
