<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 17:26
 */

namespace unit\src\Huruk\Application;


use Huruk\Application\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEventDispatcherService()
    {
        $this->assertInstanceOf(
            '\Huruk\EventDispatcher\EventDispatcher',
            Application::getService(Application::EVENT_DISPATCHER_SERVICE)
        );
    }


    public function testEvents()
    {
        $value = 5;
        $function = function () use ($value) {
            $this->assertEquals(5, $value);
        };
        Application::listen(
            'foo',
            $function
        );

        Application::trigger('foo');
    }
}
