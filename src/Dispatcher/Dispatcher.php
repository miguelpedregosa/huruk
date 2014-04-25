<?php
namespace Huruk\Dispatcher;

use Huruk\Application\Application;
use Huruk\Controller\ControllerInterface;
use Huruk\EventDispatcher\Event;
use Huruk\Exception\PageNotFoundException;
use Huruk\Routing\RouteInfo;
use Huruk\Util\StablePriorityQueue;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

class Dispatcher
{
    const EVENT_INVALID_CONTROLLER_CLASS = 'event.controller.invalid_class';
    const EVENT_INVALID_ACTION_NAME = 'event.controller.invalid_action';

    /**
     * Just for testing
     * @var bool
     */
    public static $sendHeaders = true;
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * A partir de la informacion de enrutado, instancia el controlador adecuado, ejecuta la accion
     * y realiza el dispatcheo del resultado
     * @param RouteInfo $routeInfo
     * @throws \Huruk\Exception\PageNotFoundException
     * @throws \Exception
     */
    public function dispatch(RouteInfo $routeInfo = null)
    {
        if (!$routeInfo instanceof RouteInfo) {
            throw new PageNotFoundException();
        }

        $response = false;
        if ($routeInfo->getClosure()) {
            $closureStorage = ClosureStorage::getInstance();
            if (isset($closureStorage[$routeInfo->getRouteName()])
                && is_callable($closureStorage[$routeInfo->getRouteName()])
            ) {
                $response = call_user_func_array(
                    $closureStorage[$routeInfo->getRouteName()],
                    array($routeInfo, $this->getRequest())
                );
            }
        }
        if (!$response) {
            $response = $this->dispatchUsingController($routeInfo);
        }

        $response = $this->normalizeResponse($response);

        //Envio el resultado de la accion al navegador
        $this->sendResponse($response);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return Dispatcher
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param RouteInfo $routeInfo
     * @return Response
     * @throws \Exception
     * @throws \Huruk\Exception\PageNotFoundException
     */
    private function dispatchUsingController(RouteInfo $routeInfo)
    {
        //Clase del controlador encargado de ejecutar la accion proveniente del enrutado
        $controller_class = $routeInfo->getControllerClass();
        if (!is_string($controller_class) || !strlen($controller_class)) {
            throw new PageNotFoundException();
        }

        if (!class_exists($controller_class)) {
            Application::trigger(
                self::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $routeInfo))
            );
            throw new \Exception('Invalid controller class');
        }

        //Intancia del controlador
        /** @var ControllerInterface|EventSubscriberInterface $controller */
        $controller = new $controller_class();
        if (!$controller instanceof ControllerInterface) {
            Application::trigger(
                self::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $routeInfo))
            );
            throw new \Exception('Invalid controller class');
        }
        
        $this->getEventDispatcher()->addSubscriber($controller);

        //Accion a ejecutar
        $action_name = $routeInfo->getAction();
        if (!strlen($action_name)) {
            Application::trigger(
                self::EVENT_INVALID_ACTION_NAME,
                new Event(array('route_info' => $routeInfo))
            );
            throw new \Exception('No action to be executed');
        }

        //Ejecuto la accion, pasando el control al Controller
        return $controller->doAction($action_name, $routeInfo, $this->getRequest());
    }

    /**
     * @return \Huruk\EventDispatcher\EventDispatcher
     */
    private function getEventDispatcher()
    {
        return Application::getService(Application::EVENT_DISPATCHER_SERVICE);
    }

    /**
     * @param $response
     * @return Response
     * @throws \Exception
     */
    private function normalizeResponse($response)
    {
        if (!$response instanceof Response) {
            if (is_string($response)) {
                $response = Response::make($response);
            } else {
                throw new \Exception('Expected Response Object');
            }
        }
        return $response;
    }

    /**
     * Enviamos el Response al navegador
     * @param Response $response
     */
    public function sendResponse(Response $response)
    {
        //Enviar los headers y enviar el contenido si hay que hacerlo
        if (self::$sendHeaders) {
            /** @var $headers StablePriorityQueue */
            $headers = $response->getHeaders();

            while (!$headers->isEmpty()) {
                /** @var $header Header */
                $header = $headers->extract();
                $header->send();
            }
        }

        if ($response->mustSendContent()) {
            echo $response->getContent();
        }
    }
}
