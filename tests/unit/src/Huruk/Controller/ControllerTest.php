<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 18:50
 */

namespace unit\src\Huruk\Controller;

use Huruk\Dispatcher\Response;
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
        $response = new Response('foo:bar');
        $controller = new DummyController();
        $this->assertEquals($response, $controller->doAction('dummyAction', new RouteInfo(), new Request()));
    }
}
