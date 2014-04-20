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

    public $sendHeaders = true;
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * A partir de la informacion de enrutado, instancia el controlador adecuado, ejecuta la accion
     * y realiza el dispatcheo del resultado
     * @param RouteInfo $route_info
     * @throws \Huruk\Exception\PageNotFoundException
     * @throws \Exception
     */
    public function dispatch(RouteInfo $route_info)
    {
        if (!$route_info instanceof RouteInfo) {
            throw new PageNotFoundException();
        }

        //Clase del controlador encargado de ejecutar la accion proveniente del enrutado
        $controller_class = $route_info->getControllerClass();
        if (!is_string($controller_class) || !strlen($controller_class)) {
            throw new PageNotFoundException();
        }


        if (!class_exists($controller_class)) {
            Application::trigger(
                self::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('Invalid controller class');
        }

        //Intancia del controlador
        /** @var ControllerInterface|EventSubscriberInterface $controller */
        $controller = new $controller_class();
        if (!$controller instanceof ControllerInterface) {
            Application::trigger(
                self::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('Invalid controller class');
        }
        //$controller->setApplication();
        $this->getEventDispatcher()->addSubscriber($controller);

        //Accion a ejecutar
        $action_name = $route_info->getAction();
        if (!strlen($action_name)) {
            Application::trigger(
                self::EVENT_INVALID_ACTION_NAME,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('No action to be executed');
        }

        //Ejecuto la accion, pasando el control al Controller
        $request = $this->request;
        $response = $controller->doAction($action_name, $route_info, $request);

        //Envio el resultado de la accion al navegador
        $this->sendResponse($response);
    }

    /**
     * @return \Huruk\EventDispatcher\EventDispatcher
     */
    private function getEventDispatcher()
    {
        return Application::getService(Application::EVENT_DISPATCHER_SERVICE);
    }


    /**
     * Enviamos el Response al navegador
     * @param Response $response
     */
    public function sendResponse(Response $response)
    {
        //Enviar los headers y enviar el contenido si hay que hacerlo
        if ($this->sendHeaders) {
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
