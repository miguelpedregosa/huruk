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
        require_once __DIR__ . '/sut/DummyAction.php';
    }

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher();
        $request = new Request();
        $routeInfo = new RouteInfo(
            array(
                '_action' => '\unit\Dispatcher\sut\DummyAction',
                '_route' => 'dummyRoute'
            )
        );
        $response = $dispatcher->handleRequest($request, $routeInfo);
        $this->assertEquals('foo:bar', $response->getContent());
    }

    public function testNonInstanciableAction ()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher();
        $routeInfo = new RouteInfo(
            array(
                '_action' => '\Huruk\Action\Action',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->handleRequest(new Request(), $routeInfo);
    }

    public function testNoActionxception()
    {
        $this->setExpectedException('\Exception');
        $dispatcher = new Dispatcher();
        $routeInfo = new RouteInfo(
            array(
                '_action' => '',
                '_route' => 'dummyRoute'
            )
        );
        $dispatcher->handleRequest(new Request(), $routeInfo);
    }
}
