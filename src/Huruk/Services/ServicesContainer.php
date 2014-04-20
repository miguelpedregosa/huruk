<?php

namespace Huruk\Services;


class ServicesContainer
{
    private $closures = array();
    private $services = array();

    /**
     * @param $name
     * @return mixed
     */
    public function getService($name)
    {
        if (!isset($this->services[$name])) {
            if (isset($this->closures[$name]) && is_callable($this->closures[$name])) {
                $this->services[$name] = call_user_func($this->closures[$name]);
            } else {
                $this->services[$name] = null;
            }
        }
        return $this->services[$name];
    }

    /**
     * @param $name
     * @param callable $service
     */
    public function registerService($name, \Closure $service)
    {
        $this->closures[$name] = $service;
        if (isset($this->services[$name])) {
            $this->services[$name] = null;
        }
    }
}
