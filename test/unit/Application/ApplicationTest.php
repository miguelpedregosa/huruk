<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 17:26
 */

namespace unit\src\Huruk\Application;


use Huruk\Application\Huruk;
use Huruk\Dispatcher\Responder;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use unit\Application\sut\DummyHuruk;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/../Dispatcher/sut/DummyAction.php';
        require_once __DIR__ . '/sut/DummyApplication.php';
    }


    public function testGetEventDispatcherService()
    {
        $this->assertInstanceOf(
            '\Huruk\EventDispatcher\EventDispatcher',
            Huruk::getService(Huruk::EVENT_DISPATCHER_SERVICE)
        );
    }


    public function testEvents()
    {
        $value = 5;
        $function = function () use ($value) {
            $this->assertEquals(5, $value);
        };
        Huruk::listen(
            'foo',
            $function
        );

        Huruk::trigger('foo');
    }

    public function testStaticRun()
    {
        $collection = new RouteCollection();
        $collection->add(
            'foo',
            new Route(
                '/foo',
                array(
                    RouteInfo::ACTION => '\unit\Dispatcher\sut\DummyAction'
                )
            )
        );

        Huruk::setRouteCollection($collection);
        $request = Request::create('http://example.com/foo');
        ob_start();
        Huruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);
    }

    public function testRun()
    {
        $request = Request::create('http://example.com/foo');

        ob_start();
        DummyHuruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);

    }

    public function testInvalidPath()
    {
        $request = Request::create('http://example.com/not_setted_route');

        ob_start();
        DummyHuruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertNotContains('foo:bar', $output);
        $this->assertContains('Not Found', $output);

    }

    public function testStaticGet()
    {
        $closure = function () {
            $response = new Responder('foo:bar');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/get_route');
        ob_start();
        Huruk::get('/get_route', $closure);
        Huruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('foo:bar', $output);
    }

    public function testGet()
    {
        $closure = function () {
            $response = new Responder('foo:bar');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/get_route');
        ob_start();
        DummyHuruk::get('/get_route', $closure);
        DummyHuruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('foo:bar', $output);
    }

    public function testStaticPost()
    {
        $closure = function () {
            $response = new Responder('One->Two');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/post_route', 'POST');
        ob_start();
        Huruk::post('/post_route', $closure);
        Huruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('One->Two', $output);
    }

    public function testPost()
    {
        $closure = function () {
            $response = new Responder('One->Two');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/post_route', 'POST');
        ob_start();
        DummyHuruk::post('/post_route', $closure);
        DummyHuruk::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('One->Two', $output);
    }
}
