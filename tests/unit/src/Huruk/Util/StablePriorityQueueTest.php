<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 11:45
 */

namespace unit\src\Huruk\Util;


use Huruk\Util\StablePriorityQueue;

class StablePriorityQueueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers StablePriorityQueue::insert
     */
    public function testPriority()
    {
        $queue = new StablePriorityQueue();
        $first = '1';
        $second = '2';
        $queue->insert($first, 1);
        $queue->insert($second, 2);

        $this->assertFalse($queue->isEmpty(), 'Queue has elements');
        $this->assertEquals($second, $queue->extract(), 'Second element has more priority than first one');
        $this->assertEquals($first, $queue->extract(), 'First element has less priority than seconde one');
    }

    /**
     * @covers StablePriorityQueue::insert
     */
    public function testEqualPriority()
    {
        $queue = new StablePriorityQueue();
        $first = '1';
        $second = '2';

        $queue->insert($first, 1);
        $queue->insert($second, 1);

        $this->assertFalse($queue->isEmpty(), 'Queue has elements');
        $this->assertEquals($first, $queue->extract(), 'First element was inserted before');
        $this->assertEquals($second, $queue->extract(), 'Second element was inserted after');

    }

}
