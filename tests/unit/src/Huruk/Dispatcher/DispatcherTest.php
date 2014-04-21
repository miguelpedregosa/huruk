<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 19:58
 */

namespace unit\src\Huruk\Dispatcher;


use Huruk\Dispatcher\Dispatcher;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/../Controller/sut/DummyController.php';
    }

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher(new Request());
        $dispatcher::$sendHeaders = false;
        $route_info = new RouteInfo(
            array(
                '_controller' => '\unit\src\Huruk\Controller\sut\DummyController',
                '_action' => 'dummyAction',
                '_route' => 'dummyRoute'
            )
        );
        ob_start();
        $dispatcher->dispatch($route_info);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);
    }

    public function testNoRouteInfoException()
    {
        $this->setExpectedException('\Huruk\Exception\PageNotFoundException');
        $dispatcher = new Dispatcher(new Request());
        $dispatcher->dispatch();
    }

    public function testNoControllerException()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher(new Request());
        $route_info = new RouteInfo(
            array(
                '_controller' => '',
                '_action' => 'dummyAction',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->dispatch($route_info);
    }

    public function testInvalidControllerException()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher(new Request());
        $route_info = new RouteInfo(
            array(
                '_controller' => '\foo\Bar',
                '_action' => 'dummyAction',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->dispatch($route_info);
    }

    public function testInvalidControllerClassException()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher(new Request());
        $route_info = new RouteInfo(
            array(
                '_controller' => __CLASS__,
                '_action' => 'dummyAction',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->dispatch($route_info);
    }

    public function testNoActionrException()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher(new Request());
        $route_info = new RouteInfo(
            array(
                '_controller' => '\unit\src\Huruk\Controller\sut\DummyController',
                '_action' => '',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->dispatch($route_info);
    }
}
