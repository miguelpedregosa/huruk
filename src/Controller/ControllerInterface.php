<?php
namespace Huruk\Controller;


use Huruk\Dispatcher\Response;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

interface ControllerInterface
{

    /**
     * @param $action
     * @param RouteInfo $routeInfo
     * @param Request $request
     * @return Response
     */
    public function doAction($action, RouteInfo $routeInfo, Request $request);
}
