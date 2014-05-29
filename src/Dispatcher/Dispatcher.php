<?php
namespace Huruk\Dispatcher;

use Huruk\Action\Action;
use Huruk\Application\Huruk;
use Huruk\EventDispatcher\Event;
use Huruk\Routing\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

class Dispatcher
{
    const EVENT_PREACTION = 'event.preaction';
    const EVENT_POSTACTION = 'event.postaction';

    /**
     * Handle a Request and return the Response to the app
     * @param Request $request
     * @param RouteInfo $routeInfo
     * @return Responder|mixed|null
     * @throws \Exception
     */
    public function handleRequest(Request $request, RouteInfo $routeInfo)
    {
        $response = $this->handleUsingClosure($request, $routeInfo);
        if (!$response) {
            $response = $this->handleUsingAction($request, $routeInfo);
        }
        $response = $this->normalizeResponse($response);
        return $response;
    }

    /**
     * @param RouteInfo $routeInfo
     * @param Request $request
     * @return mixed|null
     */
    private function handleUsingClosure(Request $request, RouteInfo $routeInfo)
    {
        $response = null;
        $routeName = $routeInfo->getRouteName();
        $closureStorage = ClosureStorage::getInstance();
        if (isset($closureStorage[$routeName])
            && is_callable($closureStorage[$routeName])
        ) {
            $this->triggerPreActionEvent($routeInfo);
            $response = call_user_func_array(
                $closureStorage[$routeName],
                array($request, $routeInfo)
            );
            $this->triggerPostActionEvent($routeInfo);
        }
        return $response;
    }

    /**
     * @param $routeInfo
     */
    private function triggerPreActionEvent(RouteInfo $routeInfo)
    {
        Huruk::trigger(
            self::EVENT_PREACTION,
            new Event(
                array(
                    'routeInfo' => $routeInfo
                )
            )
        );
    }

    /**
     * @param RouteInfo $routeInfo
     */
    private function triggerPostActionEvent(RouteInfo $routeInfo)
    {
        Huruk::trigger(
            self::EVENT_POSTACTION,
            new Event(
                array(
                    'routeInfo' => $routeInfo
                )
            )
        );
    }

    /**
     * @param RouteInfo $routeInfo
     * @param Request $request
     * @return Responder
     * @throws \Exception
     */
    private function handleUsingAction(Request $request, RouteInfo $routeInfo)
    {
        $actionClass = $routeInfo->getAction();
        if (!$actionClass) {
            throw new \Exception();
        }
        $reflectionClass = new \ReflectionClass($actionClass);
        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception();
        }
        $action = new $actionClass();
        if (!$action instanceof Action) {
            throw new \Exception();
        }

        $this->triggerPreActionEvent($routeInfo);
        $response = $action->execute($request, $routeInfo);
        $this->triggerPostActionEvent($routeInfo);
        return $response;
    }

    /**
     * @param $response
     * @return Responder
     * @throws \Exception
     */
    private function normalizeResponse($response)
    {
        if (!$response instanceof Responder) {
            if (is_string($response)) {
                $response = new Responder($response);
            } else {
                throw new \Exception('Expected Response Object');
            }
        }
        return $response;
    }
}
