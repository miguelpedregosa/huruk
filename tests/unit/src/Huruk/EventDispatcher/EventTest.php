<?php
/**
 *
 * User: migue
 * Date: 23/03/14
 * Time: 10:54
 */

namespace unit\src\Huruk\EventDispatcher;


use Huruk\EventDispatcher\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover Event::__construct
     * @cover Event::getData
     */
    public function testDataEvent()
    {
        $data = array('foo' => 'bar');
        $event = new Event($data);
        $this->assertEquals($data, $event->getData());
    }

    /**
     * @cover Event::setData
     */
    public function testSetData()
    {
        $data = array('foo' => 'bar');
        $event = new Event();
        $event->setData($data);
        $this->assertEquals($data, $event->getData());
    }

    /**
     * @cover Event::make
     */
    public function testFactory()
    {
        $data = array('foo' => 'bar');
        $event = Event::make($data);
        $this->assertEquals($data, $event->getData());
    }

    /**
     * @cover Event::offsetExists
     * @cover Event::offsetGet
     * @cover Event::offsetSet
     * @cover Event::offsetUnset
     */
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
