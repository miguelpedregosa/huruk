<?php
/**
 *
 * User: migue
 * Date: 11/05/14
 * Time: 18:00
 */

namespace unit\Dispatcher\sut;


use Huruk\Action\Action;
use Huruk\Dispatcher\Responder;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

class DummyAction implements Action
{

    /**
     * @param Request $request
     * @param RouteInfo $routeInfo
     * @return Responder
     */
    public function execute(Request $request, RouteInfo $routeInfo)
    {
        $response = new Responder('foo:bar');
        $response->disableSendHeaders();
        return $response;
    }
}
