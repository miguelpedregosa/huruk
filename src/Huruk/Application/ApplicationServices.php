<?php
/**
 *
 * User: migue
 * Date: 20/04/14
 * Time: 14:21
 */

namespace Huruk\Application;


use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Services\ServicesContainer;

class ApplicationServices extends ServicesContainer
{

    public function __construct()
    {
        //Registramos servicios comunes

        //Event Dispatcher
        $this->registerEventDispatcherService();


    }

    private function registerEventDispatcherService()
    {
        $this->registerService(
            Application::EVENT_DISPATCHER_SERVICE,
            function () {
                return new EventDispatcher();
            }
        );
    }
}
