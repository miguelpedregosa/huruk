<?php
/**
 * User: migue
 * Date: 9/04/14
 * Time: 16:12
 */

namespace unit\src\Huruk\Routing;


use Huruk\Routing\Router;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Router */
    private $router;

    public function setUp()
    {
        parent::setUp();
        $route_collection = new RouteCollection();
        $route_collection->add('foo', new Route('/foo', array('controller' => 'FooController')));
        $route_collection->add('bar', new Route('/bar', array('controller' => 'BarController')));

        $request_context = new RequestContext('/');
        $this->router = new Router($route_collection, $request_context);
    }
}
