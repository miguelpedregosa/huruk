<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 17:26
 */

namespace unit\src\Huruk\Application;


use Huruk\Application\Application;
use Huruk\Dispatcher\Dispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/../Controller/sut/DummyController.php';
    }


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

    public function testRun()
    {
        $collection = new RouteCollection();
        $collection->add(
            'foo',
            new Route(
                '/foo',
                array(
                    '_controller' => '\unit\src\Huruk\Controller\sut\DummyController',
                    '_action' => 'dummyAction'
                )
            )
        );

        $request = Request::create('http://example.com/foo');

        ob_start();
        Dispatcher::$sendHeaders = false;
        Application::run($collection, $request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);

    }

    public function testInvalidPath()
    {
        $collection = new RouteCollection();
        $collection->add(
            'bar',
            new Route(
                '/bar',
                array(
                    '_controller' => '\unit\src\Huruk\Controller\sut\DummyController',
                    '_action' => 'dummyAction'
                )
            )
        );

        $request = Request::create('http://example.com/foo');

        ob_start();
        Dispatcher::$sendHeaders = false;
        Application::run($collection, $request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertNotContains('foo:bar', $output);
        $this->assertContains('<title>Huruk µFramework - Not Found</title>', $output);

    }
}
