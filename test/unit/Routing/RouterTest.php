<?php
/**
 * User: migue
 * Date: 9/04/14
 * Time: 16:12
 */

namespace unit\src\Huruk\Routing;

use Huruk\Routing\RouteInfo;
use Huruk\Routing\Router;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use \Mockery;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Router */
    private $router;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        parent::setUp();
        $this->router = new Router();

        $route_collection = new RouteCollection();
        $route_collection->add('foo', new Route('/foo', array('_controller' => 'FooController')));
        $route_collection->add('bar', new Route('/bar', array('_controller' => 'BarController')));
        $this->router->setRouteCollection($route_collection);

        $request_context = new RequestContext();
        $this->router->setRequestContext($request_context);
    }

    public function testClass()
    {
        $this->assertInstanceOf('\Huruk\Routing\Router', $this->router);
    }

    public function testMatchUrl()
    {
        $expected = new RouteInfo(
            array(
                '_controller' => 'FooController',
                '_route' => 'foo'
            )
        );

        $this->assertEquals($expected, $this->router->matchUrl('/foo'));

        $expected = new RouteInfo(
            array(
                '_controller' => 'BarController',
                '_route' => 'bar'
            )
        );
        $this->assertEquals($expected, $this->router->matchUrl('/bar'));
    }

    public function testGenerateUrl()
    {
        $this->assertEquals('/foo', $this->router->generateUrl('foo'));
        $this->assertEquals('/bar', $this->router->generateUrl('bar'));
    }

    public function testNotFoundRoute()
    {
        $this->setExpectedException('\Huruk\Exception\PageNotFoundException');
        $this->router->matchUrl('/false');
    }

    public function testNoRouteCollectionException()
    {
        $this->setExpectedException('\Huruk\Exception\PageNotFoundException');
        $router = new Router();
        $router->matchUrl('/route');
    }

    public function testNoRquestContextCollection()
    {
        $this->setExpectedException('\Huruk\Exception\PageNotFoundException');
        $new_router = new Router();
        $route_collection = new RouteCollection();
        $route_collection->add('foo', new Route('/foo', array('_controller' => 'FooController')));
        $new_router->setRouteCollection($route_collection);
        $new_router->matchUrl('/route');
    }

    public function testLogger()
    {
        /** @var \Psr\Log\LoggerInterface|\Mockery\MockInterface $logger */
        $logger = Mockery::mock('\Psr\Log\LoggerInterface');
        $logger->shouldReceive('debug')->once();
        $this->router->setLogger($logger);

        $expected = new RouteInfo(
            array(
                '_controller' => 'FooController',
                '_route' => 'foo'
            )
        );
        $this->assertEquals($expected, $this->router->matchUrl('/foo'));
    }
}
