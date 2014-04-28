<?php
namespace Huruk\Services;


class ServicesContainer
{
    private $closures = array();
    private $services = array();

    /**
     * @param $serviceName
     * @param bool $shareInstance
     * @return mixed
     */
    public function getService($serviceName, $shareInstance = true)
    {
        if ($shareInstance) {
            if (!isset($this->services[$serviceName])) {
                $this->services[$serviceName] = $this->createServiceInstance($serviceName);
            }
            return $this->services[$serviceName];
        } else {
            return $this->createServiceInstance($serviceName);
        }
    }

    /**
     * @param $serviceName
     * @return mixed|null
     */
    private function createServiceInstance($serviceName)
    {
        return isset($this->closures[$serviceName]) && is_callable($this->closures[$serviceName])
            ? call_user_func($this->closures[$serviceName]) : null;
    }

    /**
     * @param $serviceName
     * @param callable $service
     */
    public function registerService($serviceName, \Closure $service)
    {
        $this->closures[$serviceName] = $service;
        if (isset($this->services[$serviceName])) {
            $this->services[$serviceName] = null;
        }
    }
}
