<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 19:06
 */

namespace unit\src\Huruk\Controller\sut;


use Huruk\Controller\Controller;
use Huruk\Dispatcher\Response;

class DummyController extends Controller
{
    public $value = 0;

    public static function getSubscribedEvents()
    {
        return array('foo.event' => array('onFooEvent'));
    }

    public function dummyAction()
    {
        return Response::make('foo:bar');
    }

    public function invalidAction()
    {
        return false;
    }

    public function stringAction()
    {
        return 'foo:bar';
    }

    public function onFooEvent()
    {
        $this->value = 100;
    }
}
