<?php
/**
 *
 * User: migue
 * Date: 24/11/13
 * Time: 21:14
 */

namespace Huruk\Application;

use Assetic\Asset\AssetCollection;
use Huruk\Debug\DebugWebBar;
use Huruk\Dispatcher\Dispatcher;
use Huruk\EventDispatcher\Event;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

interface ApplicationInterface
{

    /**
     * @param $event_name
     * @param $listener
     * @param int $priority
     */
    public static function listen($event_name, $listener, $priority = 0);

    /**
     * @param EventSubscriberInterface $subscriberInterface
     */
    public static function addSubscriber(EventSubscriberInterface $subscriberInterface);

    /**
     * @param EventSubscriberInterface $subscriberInterface
     */
    public static function removeSubscriber(EventSubscriberInterface $subscriberInterface);

    /**
     * @param $event_name
     * @param $listener
     */
    public static function removeListener($event_name, $listener);


    /**
     * @param Request $request
     */
    public static function setRequest(Request $request);

    /**
     * Devuelve el nombre de la aplicacion
     * @return string
     */
    public static function getName();

    /**
     * @param $route_name
     * @param array $params
     * @param bool $referenceType
     * @return null|string
     */
    public static function generateUrl($route_name, $params = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH);

    /**
     * @return null|Request
     */
    public static function getRequest();

    /**
     * Renderiza una plantilla usando Twig
     * @param $template_name
     * @param array $context
     * @return string
     */
    public static function renderTemplate($template_name, $context = array());

    /**
     * @return \Swift_Mailer
     */
    public static function mail();

    /**
     * Procesa una peticion http dirgida a la aplicacion
     */
    public static function run();

    /**
     * @return DebugWebBar
     */
    public static function getDebugBar();

    /**
     * @return Logger
     */
    public static function log();

    /**
     * @param $event_name
     * @param Event $event
     */
    public static function trigger($event_name, Event $event = null);

    /**
     * Devuelve la instancia del Dispatcher asociada a la aplicacion
     * @return Dispatcher
     */
    public static function getDispatcher();

    /**
     * @param array $inputs
     * @param array $filters
     * @param array $options
     * @return AssetCollection
     */
    public static function createAsset($inputs = array(), $filters = array(), array $options = array());

}
