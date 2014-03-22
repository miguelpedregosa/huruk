<?php
/**
 * Clase base de cualquier aplicacion
 * User: migue
 * Date: 24/11/13
 * Time: 15:50
 */

namespace Huruk\Application;

use Assetic\AssetManager;
use Assetic\Factory\AssetFactory;
use Assetic\Factory\Worker\CacheBustingWorker;
use Assetic\FilterManager;
use DebugBar\Bridge\MonologCollector;
use DebugBar\Bridge\SwiftMailer\SwiftLogCollector;
use DebugBar\Bridge\SwiftMailer\SwiftMailCollector;
use DebugBar\Bridge\Twig\TraceableTwigEnvironment;
use DebugBar\Bridge\Twig\TwigCollector;
use Huruk\Debug\DebugWebBar;
use Huruk\Dispatcher\Dispatcher;
use Huruk\Dispatcher\Response;
use Huruk\EventDispatcher\Event;
use Huruk\EventDispatcher\EventDispatcher;
use Huruk\Exception\PageNotFoundException;
use Huruk\Layout\Html5Layout;
use Huruk\Routing\RouteInfo;
use Huruk\Routing\Router;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

abstract class Application implements ApplicationInterface
{

    const LOGGER_SERVICE = 'logger_service';
    const EVENT_DISPATCHER_SERVICE = 'event_dispatcher_service';
    const ROUTER_SERVICE = 'router_service';
    const TEMPLATE_SERVICE = 'template_service';
    const MAIL_SERVICE = 'mail_service';
    const DISPATCHER_SERVICE = 'dispatcher_service';
    const ASSETS_FACTORY_SERVICE = 'assets_factory_service';

    const REQUEST = 'request';

    /**
     * @var
     */
    protected static $instance;

    /**
     * @var
     */
    protected $services_container = array();

    /**
     *
     */
    private function __construct()
    {

    }


    /**
     * Permite añadir un listener para el event dispatcher de la aplicacion
     * @param $event_name
     * @param $listener
     * @param int $priority
     */
    public static function listen($event_name, $listener, $priority = 0)
    {
        static::getInstance()->getLoggerService()->addDebug('Added listener to event ' . $event_name);
        static::getInstance()->getEventDispatcherService()->addListener($event_name, $listener, $priority);
    }

    /**
     * Da acceso al servicio de log
     * @return Logger
     */
    private function getLoggerService()
    {
        if (!isset($this->services_container[self::LOGGER_SERVICE]) ||
            !$this->services_container[self::LOGGER_SERVICE] instanceof Logger
        ) {
            $logger = $this->getLogger();
            DebugWebBar::getInstance()->addCollector(new MonologCollector($logger));
            $this->services_container[self::LOGGER_SERVICE] = $logger;
        }
        return $this->services_container[self::LOGGER_SERVICE];
    }

    /**
     * Devuelve el logger a usar por la aplicacion
     * @return Logger
     */
    abstract protected function getLogger();

    /**
     * @return Application
     */
    protected static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return EventDispatcher
     */
    private function getEventDispatcherService()
    {
        if (!isset($this->services_container[self::EVENT_DISPATCHER_SERVICE])
            || !$this->services_container[self::EVENT_DISPATCHER_SERVICE] instanceof EventDispatcher
        ) {
            $this->services_container[self::EVENT_DISPATCHER_SERVICE] = new EventDispatcher();
        }
        return $this->services_container[self::EVENT_DISPATCHER_SERVICE];
    }

    /**
     * @param EventSubscriberInterface $subscriber
     */
    public static function addSubscriber(EventSubscriberInterface $subscriber)
    {
        static::getInstance()->getLoggerService()->addDebug('Added subscriber ' . get_class($subscriber));
        static::getInstance()->getEventDispatcherService()->addSubscriber($subscriber);
    }

    /**
     * @param EventSubscriberInterface $subscriberInterface
     */
    public static function removeSubscriber(EventSubscriberInterface $subscriberInterface)
    {
        static::getInstance()->getLoggerService()->addDebug('Removed subscriber ' . get_class($subscriberInterface));
        static::getInstance()->getEventDispatcherService()->removeSubscriber($subscriberInterface);
    }

    /**
     * @param $event_name
     * @param $listener
     */
    public static function removeListener($event_name, $listener)
    {
        static::getInstance()->getLoggerService()->addDebug('Removed listener to event ' . $event_name);
        static::getInstance()->getEventDispatcherService()->removeListener($event_name, $listener);
    }

    /**
     * @param Request $request
     */
    public static function setRequest(Request $request)
    {
        static::getInstance()->setRequestIntoContainer($request);
    }

    /**
     * @param Request $request
     * @return $this
     */
    private function setRequestIntoContainer(Request $request)
    {
        $this->services_container[self::REQUEST] = $request;
    }

    /**
     * @param $route_name
     * @param array $params
     * @param bool $referenceType
     * @return null|string
     */
    public static function generateUrl($route_name, $params = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return static::getInstance()->getRouter()->generateUrl($route_name, $params, $referenceType);
    }

    /**
     * Devuelve la instancia del enrutador que se debe usar
     * @return Router|null
     */
    private function getRouter()
    {
        if (!isset($this->services_container[self::ROUTER_SERVICE])
            || !$this->services_container[self::ROUTER_SERVICE] instanceof Router
        ) {
            $router = new Router($this->getRouteCollection(), $this->getRequestContext(), $this->getLoggerService());
            $router->setApplication($this);
            $this->services_container[self::ROUTER_SERVICE] = $router;
        }
        return $this->services_container[self::ROUTER_SERVICE];
    }

    /**
     * Devuelve la coleccion de rutas a usar por el enrutador de peticiones
     * @return RouteCollection
     */
    abstract protected function getRouteCollection();

    /**
     * @return mixed|RequestContext
     */
    private function getRequestContext()
    {
        $request_context = new RequestContext();
        $request_context->fromRequest($this->getRequest());
        return $request_context;
    }

    /**
     * @return null|Request
     */
    public static function getRequest()
    {
        return static::getInstance()->getRequestFromContainer();
    }

    /**
     * @return null|Request
     */
    private function getRequestFromContainer()
    {
        if (!isset($this->services_container[self::REQUEST]) || !$this->services_container[self::REQUEST] instanceof Request) {
            $this->services_container[self::REQUEST] = Request::createFromGlobals();
        }
        return $this->services_container[self::REQUEST];
    }

    /**
     * Renderiza una plantilla usando Twig
     * @param $template_name
     * @param array $context
     * @return string
     */
    public static function renderTemplate($template_name, $context = array())
    {
        return static::getInstance()->getTemplateService()->render($template_name, $context);
    }

    /**
     *
     * @return \Twig_Environment
     */
    private function getTemplateService()
    {
        if (!isset($this->services_container[self::TEMPLATE_SERVICE])
            || !$this->services_container[self::TEMPLATE_SERVICE] instanceof \Twig_Environment
        ) {
            //Opciones para Twig
            $options = $this->getTemplatesOptions();

            //Loader de templates
            $paths = $this->getTemplatesPaths();
            $loader = $this->getTemplatesLoader($paths);

            //Opciones finales para Twig
            $options = array_merge(
                array(
                    'debug' => $this->isDebugOn(),
                    'cache' => '/tmp/twigcache',
                    'strict_variables' => $this->isDebugOn(),
                    'auto_reload' => true
                ),
                $options
            );
            $twig = new \Twig_Environment($loader, $options);

            if ($options['debug']) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            if (DebugWebBar::isEnabled()) {
                $twig = new TraceableTwigEnvironment($twig);
                DebugWebBar::getInstance()->addCollector(new TwigCollector($twig));
            }

            $this->services_container[self::TEMPLATE_SERVICE] = $twig;
        }
        return $this->services_container[self::TEMPLATE_SERVICE];
    }

    /**
     * @return array
     */
    protected function getTemplatesOptions()
    {
        return array();
    }

    /**
     * @return array
     */
    abstract protected function getTemplatesPaths();

    /**
     * Devuelve el loader de templates a usar con Twig.
     * @param array $paths
     * @return \Twig_Loader_Filesystem
     */
    protected function getTemplatesLoader($paths = array())
    {
        return new \Twig_Loader_Filesystem($paths);
    }

    /**
     * Devuelve true si aplicación está configurada en modo Debug
     * @return bool
     */
    abstract protected function isDebugOn();


    /**
     * @param array $inputs
     * @param array $filters
     * @param array $options
     * @return \Assetic\Asset\AssetCollection
     */
    public static function createAsset($inputs = array(), $filters = array(), array $options = array())
    {
        return static::getInstance()->getAssetsFactoryService()->createAsset($inputs, $filters, $options);
    }

    /**
     * @return AssetFactory
     */
    private function getAssetsFactoryService()
    {
        if (!isset($this->services_container[self::ASSETS_FACTORY_SERVICE])
            || !$this->services_container[self::ASSETS_FACTORY_SERVICE] instanceof AssetFactory
        ) {
            $asset_factory = new AssetFactory($this->getWebRootPath(), $this->isDebugOn());
            $asset_factory->setDefaultOutput('ac/*');
            $asset_manager = new AssetManager();
            $asset_factory->setAssetManager($asset_manager);
            $filter_manager = new FilterManager();
            $asset_factory->setFilterManager($filter_manager);
            $asset_factory->addWorker(new CacheBustingWorker());

            $this->services_container[self::ASSETS_FACTORY_SERVICE] = $asset_factory;
        }
        return $this->services_container[self::ASSETS_FACTORY_SERVICE];
    }

    /**
     * Devuelve el directorio raiz de los recursos (js, css, ...) de la aplicacion
     * @return string
     */
    abstract protected function getWebRootPath();

    /**
     * @return \Swift_Mailer
     */
    public static function mail()
    {
        return static::getInstance()->getMailerService();
    }

    /**
     * @return mixed
     */
    private function getMailerService()
    {
        if (!isset($this->services_container[self::MAIL_SERVICE])
            || !$this->services_container[self::MAIL_SERVICE] instanceof \Swift_Mailer
        ) {
            $mailer = $this->getMailer();
            $debugbar = DebugWebBar::getInstance();
            $debugbar['messages']->aggregate(new SwiftLogCollector($mailer));
            $debugbar->addCollector(new SwiftMailCollector($mailer));
            $this->services_container[self::MAIL_SERVICE] = $mailer;
        }
        return $this->services_container[self::MAIL_SERVICE];
    }

    /**
     * @return \Swift_Mailer
     */
    abstract protected function getMailer();

    /**
     * Procesa una peticion http dirgida a la aplicacion
     */
    public static function run()
    {
        $application = static::getInstance();
        $debug_bar = static::getDebugBar();
        $logger = $application->getLoggerService();
        $time_id = $debug_bar->startMeasure('Ejecución de la aplicación');

        try {
            //Obtener el path info de la request
            $timer = $debug_bar->startMeasure('Obtener path info');
            $path_info = $application->getRequestContext()->getPathInfo();
            $debug_bar->stopMeasure($timer);
            $logger->addDebug('Path info de la petición', array($path_info));

            //Proceso de enrutado de la peticion
            $timer = $debug_bar->startMeasure('Enrutado');
            $route_params = $application->getRouter()->matchUrl($path_info);
            $debug_bar->stopMeasure($timer);
            $route_info = new RouteInfo($route_params);
            $logger->addDebug('Route params', $route_params);

            //Una vez que hemos enrutado, pasamos el control al dispatcher para que se encargue de manejar la peticion
            $timer = $debug_bar->startMeasure('Dispatch de la petición');
            static::getDispatcher()->dispatch($route_info);
            $debug_bar->stopMeasure($timer);
        } catch (PageNotFoundException $e) {
            //Dejamos a la aplicacion que haga lo que quiera si detectamos un error 404
            $application->handlePageNotFoundException($e);
        } catch (\Exception $e) {
            $application->handleException($e);
        } finally {
            $debug_bar->stopMeasure($time_id);
        }
    }

    /**
     * @return DebugWebBar
     */
    public static function getDebugBar()
    {
        return DebugWebBar::getInstance();
    }

    /**
     * Devuelve la instancia del Dispatcher asociada a la aplicacion
     * @return Dispatcher
     */
    public static function getDispatcher()
    {
        return static::getInstance()->getDispatcherService();
    }

    /**
     * @return Dispatcher
     */
    private function getDispatcherService()
    {
        if (!isset($this->services_container[self::DISPATCHER_SERVICE])
            || !$this->services_container[self::DISPATCHER_SERVICE] instanceof Dispatcher
        ) {
            $dispatcher = new Dispatcher();
            $dispatcher->setApplication($this);
            $this->services_container[self::DISPATCHER_SERVICE] = $dispatcher;
        }
        return $this->services_container[self::DISPATCHER_SERVICE];
    }

    /**
     * @param PageNotFoundException $e
     */
    protected function handlePageNotFoundException(PageNotFoundException $e)
    {
        $path_info = $this->getRequestContext()->getPathInfo();
        DebugWebBar::getInstance()->addException($e);
        $document = new Html5Layout();
        $this->getDispatcher()->sendResponse(Response::make($document->render(''), 404));
        $this->getLoggerService()->addWarning($e->getMessage(), array($path_info, $e));
    }

    /**
     * @param \Exception $e
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    protected function handleException(\Exception $e)
    {
        DebugWebBar::getInstance()->addException($e);
        $this->getLoggerService()->addError($e->getMessage(), array($e));
        throw new Exception($e->getMessage());
    }

    /**
     * @param $event_name
     * @param Event $event
     */
    public static function trigger($event_name, Event $event = null)
    {
        static::getInstance()->getLoggerService()->addDebug('Trigger event: ' . $event_name, array($event->getData()));
        static::getInstance()->getEventDispatcherService()->dispatch($event_name, $event);
    }

    /**
     * @return Logger
     */
    public static function log()
    {
        return static::getInstance()->getLoggerService();
    }

    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
