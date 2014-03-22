<?php
/**
 *
 * User: migue
 * Date: 9/02/14
 * Time: 15:32
 */

namespace Huruk\Dispatcher;

use Huruk\Application\ApplicationAccess;
use Huruk\Application\ApplicationInterface;
use Huruk\Controller\ControllerInterface;
use Huruk\EventDispatcher\Event;
use Huruk\Exception\PageNotFoundException;
use Huruk\Routing\RouteInfo;
use Huruk\Util\StablePriorityQueue;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Dispatcher implements ApplicationAccess
{
    /** @var  ApplicationInterface */
    private $application;

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
            $this->getApplication()->trigger(
                Event::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('Invalid controller class');
        }

        //Intancia del controlador
        /** @var ControllerInterface|ApplicationAccess|EventSubscriberInterface $controller */
        $controller = new $controller_class($this->getApplication());
        if (!$controller instanceof ControllerInterface || !$controller instanceof ApplicationAccess) {
            $this->getApplication()->trigger(
                Event::EVENT_INVALID_CONTROLLER_CLASS,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('Invalid controller class');
        }
        $controller->setApplication($this->getApplication());
        $this->getApplication()->addSubscriber($controller);

        //Accion a ejecutar
        $action_name = $route_info->getAction();
        if (!strlen($action_name)) {
            $this->getApplication()->trigger(
                Event::EVENT_INVALID_ACTION_NAME,
                new Event(array('route_info' => $route_info))
            );
            throw new \Exception('No action to be executed');
        }

        //Ejecuto la accion, pasando el control al Controller
        $request = $this->getApplication()->getRequest();
        $response = $controller->doAction($action_name, $route_info, $request);

        //Envio el resultado de la accion al navegador
        $this->sendResponse($response);

    }

    /**
     * @return ApplicationInterface
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param ApplicationInterface $app
     * @return void
     */
    public function setApplication(ApplicationInterface $app)
    {
        $this->application = $app;
    }

    /**
     * Enviamos el Response al navegador
     * @param Response $response
     */
    public function sendResponse(Response $response)
    {
        //Enviar los headers y enviar el contenido si hay que hacerlo
        /** @var $headers StablePriorityQueue */
        $headers = $response->getHeaders();

        while (!$headers->isEmpty()) {
            /** @var $header Header */
            $header = $headers->extract();
            $header->send();
        }

        if ($response->mustSendContent()) {
            echo $response->getContent();
        }
    }
}