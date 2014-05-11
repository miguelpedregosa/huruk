<?php

namespace Huruk\Routing;

/**
 * Encapulsa los parametros provenientes del enrutado
 * Class RouteInfo
 * @package Huruk\Routing
 */
class RouteInfo
{
    const ACTION = '_action';
    const ROUTE = '_route';
    const CLOSURE = '_closure';

    private $action = null;
    private $route = null;
    private $params = array();

    /**
     * @param $routeInfo
     */
    public function __construct($routeInfo = array())
    {
        if (is_array($routeInfo)) {
            //Nombre de la ruta
            if (isset($routeInfo[self::ROUTE])) {
                $this->setRouteName($routeInfo[self::ROUTE]);
                unset($routeInfo[self::ROUTE]);
            }

            //Accion
            if (isset($routeInfo[self::ACTION])) {
                $this->setAction($routeInfo[self::ACTION]);
                unset($routeInfo[self::ACTION]);
            }
            //Resto de parametros de enrutado
            $this->setParams($routeInfo);

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
}
