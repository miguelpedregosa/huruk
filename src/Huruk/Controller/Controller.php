<?php
/**
 * Controlador base
 * User: migue
 * Date: 24/11/13
 * Time: 21:09
 */

namespace Huruk\Controller;

use Huruk\Application\Application;
use Huruk\Application\ApplicationAccess;
use Huruk\Application\ApplicationInterface;
use Huruk\Debug\DebugWebBar;
use Huruk\Dispatcher\Response;
use Huruk\EventDispatcher\Event;
use Huruk\Routing\RouteInfo;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller implements ControllerInterface, EventSubscriberInterface
{
    /** @var */
    private $application;

    public static function getSubscribedEvents()
    {
        return array();
    }

    /**
     * @param $action_name
     * @param RouteInfo $route_info
     * @param Request $request
     * @return Response|mixed
     * @throws \Exception
     */
    final public function doAction($action_name, RouteInfo $route_info, Request $request)
    {
        if (!method_exists($this, $action_name)) {
            throw new \Exception('Invalid action name');
        }

        //Evento lanzado antes de ejecutar la accion
        Application::trigger(
            Event::EVENT_PREACTION,
            new Event(
                array(
                    'action_name' => $action_name,
                    'route_info' => $route_info
                )
            )
        );

        //Ejecutamos la accion
        $response = call_user_func(array($this, $action_name), $route_info, $request);

        if (!$response instanceof Response) {
            throw new \Exception('Expected Response Object');
        }

        //Evento postAction
        Application::trigger(
            Event::EVENT_POSTACTION,
            new Event(
                array(
                    'action_name' => $action_name,
                    'route_info' => $route_info,
                    'response' => $response
                )
            )
        );

        return $response;
    }
}
