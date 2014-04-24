<?php

namespace Huruk\Routing;

/**
 * Encapulsa los parametros provenientes del enrutado
 * Class RouteInfo
 * @package Huruk\Routing
 */
class RouteInfo
{
    const CONTROLLER = '_controller';
    const ACTION = '_action';
    const ROUTE = '_route';
    const CLOSURE = '_closure';

    private $controllerClass = null;
    private $action = null;
    private $route = null;
    private $closure = false;
    private $params = array();

    /**
     * @param $route_info
     */
    public function __construct($route_info = array())
    {
        if (is_array($route_info)) {
            //Controlador
            if (isset($route_info[self::CONTROLLER])) {
                $this->setControllerClass($route_info[self::CONTROLLER]);
                unset($route_info[self::CONTROLLER]);
            }

            //Accion
            if (isset($route_info[self::ACTION])) {
                $this->setAction($route_info[self::ACTION]);
                unset($route_info[self::ACTION]);
            }

            //Nombre de la ruta
            if (isset($route_info[self::ROUTE])) {
                $this->setRouteName($route_info[self::ROUTE]);
                unset($route_info[self::ROUTE]);
            }

            //Closure
            if (isset($route_info[self::CLOSURE])) {
                $this->setClosure($route_info[self::CLOSURE]);
                unset($route_info[self::CLOSURE]);
            }

            //Resto de parametros de enrutado
            $this->setParams($route_info);

        }

    }

    /**
     * @param $route_name
     * @return RouteInfo
     */
    public function setRouteName($route_name)
    {
        $this->route = $route_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param $controller_class
     * @return RouteInfo
     */
    public function setControllerClass($controller_class)
    {
        $this->controllerClass = $controller_class;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return RouteInfo
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRouteName()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     * @return RouteInfo
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return null|\Closure
     */
    public function getClosure()
    {
        return $this->closure;
    }

    /**
     * @param $closure
     * @return RouteInfo
     */
    public function setClosure($closure)
    {
        $this->closure = $closure;
        return $this;
    }
}
