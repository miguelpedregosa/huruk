<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 18:50
 */

namespace unit\src\Huruk\Controller;

use Huruk\Application\Application;
use Huruk\Dispatcher\Response;
use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;
use unit\src\Huruk\Controller\sut\DummyController;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        require_once __DIR__ . '/sut/DummyController.php';
    }

    public function testDoAction()
    {
        $response = Response::make('foo:bar');
        $controller = new DummyController();
        $this->assertEquals($response, $controller->doAction('dummyAction', new RouteInfo(), new Request()));
    }

    public function testNoActionException()
    {
        $this->setExpectedException('\Exception');
        $controller = new DummyController();
        $controller->doAction('noAction', new RouteInfo(), new Request());
    }

    public function testInvalidAction()
    {
        $this->setExpectedException('\Exception');
        $controller = new DummyController();
        $controller->doAction('invalidAction', new RouteInfo(), new Request());
    }

    public function testStringAction()
    {
        $response = Response::make('foo:bar');
        $controller = new DummyController();
        $this->assertEquals($response, $controller->doAction('stringAction', new RouteInfo(), new Request()));
    }

    public function testEvents()
    {
        $controller = new DummyController();
        /** @var EventDispatcher $event_dispatcher */
        $event_dispatcher = Application::getService(Application::EVENT_DISPATCHER_SERVICE);
        $event_dispatcher->addSubscriber($controller);

        $event_dispatcher->trigger('foo.event');
        $this->assertEquals(100, $controller->value);
    }
}
