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
use Monolog\Handler\NullHandler;
use Monolog\Logger;

class ApplicationServices extends ServicesContainer
{

    public function __construct()
    {
        //Registramos servicios comunes

        //Event Dispatcher
        $this->registerEventDispatcherService();

        //Logger
        $this->registerLoggerService();

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

    private function registerLoggerService()
    {
        $this->registerService(
            Application::LOGGER_SERVICE,
            function () {
                $logger = new Logger('application');
                $logger->pushHandler(new NullHandler());
                return $logger;
            }
        );
    }
}
