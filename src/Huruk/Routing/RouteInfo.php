<?php
/**
 * Encapulsa los parametros provenientes del enrutado
 * User: migue
 * Date: 9/02/14
 * Time: 16:10
 */

namespace Huruk\Routing;


class RouteInfo
{
    private $controller_class = null;
    private $action = null;
    private $route = null;
    private $params = array();

    /**
     * @param $route_info
     */
    public function __construct($route_info = array())
    {
        if (is_array($route_info)) {
            //Controlador
            if (isset($route_info['_controller'])) {
                $this->setControllerClass($route_info['_controller']);
                unset($route_info['_controller']);
            }

            //Accion
            if (isset($route_info['_action'])) {
                $this->setAction($route_info['_action']);
                unset($route_info['_action']);
            }

            //Nombre de la ruta
            if (isset($route_info['_route'])) {
                $this->setRouteName($route_info['_route']);
                unset($route_info['_route']);
            }
            //Resto de parametros de enrutado
            $this->setParams($route_info);

        }

    }

    /**
     * @param $route_name
     * @return $this
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
        return $this->controller_class;
    }

    /**
     * @param $controller_class
     * @return $this
     */
    public function setControllerClass($controller_class)
    {
        $this->controller_class = $controller_class;
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
     * @return $this
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
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}
