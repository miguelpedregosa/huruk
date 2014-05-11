<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 17:26
 */

namespace unit\src\Huruk\Application;


use Huruk\Application\Application;
use Huruk\Dispatcher\ResponseFactory;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use unit\Application\sut\DummyApplication;

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

        Application::setRouteCollection($collection);
        $request = Request::create('http://example.com/foo');
        ob_start();
        Application::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);
    }

    public function testRun()
    {
        $request = Request::create('http://example.com/foo');

        ob_start();
        DummyApplication::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('foo:bar', $output);

    }

    public function testInvalidPath()
    {
        $request = Request::create('http://example.com/not_setted_route');

        ob_start();
        DummyApplication::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertNotContains('foo:bar', $output);
        $this->assertContains('Not Found', $output);

    }

    public function testStaticGet()
    {
        $closure = function () {
            $response = ResponseFactory::make('foo:bar');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/get_route');
        ob_start();
        Application::get('/get_route', $closure);
        Application::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('foo:bar', $output);
    }

    public function testGet()
    {
        $closure = function () {
            $response = ResponseFactory::make('foo:bar');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/get_route');
        ob_start();
        DummyApplication::get('/get_route', $closure);
        DummyApplication::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('foo:bar', $output);
    }

    public function testStaticPost()
    {
        $closure = function () {
            $response = ResponseFactory::make('One->Two');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/post_route', 'POST');
        ob_start();
        Application::post('/post_route', $closure);
        Application::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('One->Two', $output);
    }

    public function testPost()
    {
        $closure = function () {
            $response = ResponseFactory::make('One->Two');
            $response->disableSendHeaders();
            return $response;
        };
        $request = Request::create('http://example.com/post_route', 'POST');
        ob_start();
        DummyApplication::post('/post_route', $closure);
        DummyApplication::run($request);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('One->Two', $output);
    }
}
