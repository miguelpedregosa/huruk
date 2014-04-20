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
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher';
    const LOGGER_SERVICE = 'logger';

    public function __construct()
    {
        //Registramos servicios comunes
        $this->registerService(
            self::EVENT_DISPATCHER_SERVICE,
            function () {
                return new EventDispatcher();
            }
        );

        $this->registerService(
            self::LOGGER_SERVICE,
            function () {
                $logger = new Logger('application');
                $logger->pushHandler(new NullHandler());
                return $logger;
            }
        );

    }
}
