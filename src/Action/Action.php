<?php
/**
 *
 * User: migue
 * Date: 10/05/14
 * Time: 19:57
 */

namespace Huruk\Action;


use Huruk\Dispatcher\Response;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

interface Action
{
    /**
     * @param Request $request
     * @param RouteInfo $routeInfo
     * @return Response
     */
    public function execute(Request $request, RouteInfo $routeInfo);
}
