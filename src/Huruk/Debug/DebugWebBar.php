<?php
/**
 * Singleton de la barra de depuracion
 * User: migue
 * Date: 12/02/14
 * Time: 20:31
 */

namespace Huruk\Debug;


use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\StandardDebugBar;
use Huruk\Util\Singleton;

class DebugWebBar extends StandardDebugBar
{
    public static $enabled = true;
    private static $instance;

    /**
     * Devuelve una instancia de la clase
     * @return DebugWebBar
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function enabled()
    {
        self::$enabled = true;
    }

    public static function disable()
    {
        self::$enabled = false;
    }

    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * @param $text
     * @param array $context
     * @return null
     */
    public function addInfo($text, $context = array())
    {
        return $this->getMessagesCollector()->info($text, $context);
    }

    /**
     * @return MessagesCollector
     */
    private function getMessagesCollector()
    {
        if (!$this['messages'] instanceof MessagesCollector) {
            $this->addCollector(new MessagesCollector());
        }
        return $this['messages'];
    }

    /**
     * @param $message
     * @param array $context
     * @return null
     */
    public function addWarning($message, $context = array())
    {
        return $this->getMessagesCollector()->warning($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return null
     */
    public function addError($message, $context = array())
    {
        return $this->getMessagesCollector()->error($message, $context);
    }

    /**
     * @param $label
     * @return mixed
     */
    public function startMeasure($label)
    {
        $time_id = microtime() . md5($label);
        try {
            $this->getTimeDataCollector()->startMeasure($time_id, $label);
        } catch (\Exception $e) {
            $this->addException($e);
        }
        return $time_id;
    }

    /**
     * @return TimeDataCollector
     */
    private function getTimeDataCollector()
    {
        if (!$this['time'] instanceof TimeDataCollector) {
            $this->addCollector(new TimeDataCollector());
        }
        return $this['time'];
    }

    /**
     * @param \Exception $e
     */
    public function addException(\Exception $e)
    {
        $this->getExceptionsCollector()->addException($e);
    }

    /**
     * @return ExceptionsCollector
     */
    private function getExceptionsCollector()
    {
        if (!$this['exceptions'] instanceof ExceptionsCollector) {
            $this->addCollector(new ExceptionsCollector());
        }
        return $this['exceptions'];
    }

    /**
     * @param $time_id
     */
    public function stopMeasure($time_id)
    {
        try {
            $this->getTimeDataCollector()->stopMeasure($time_id);
        } catch (\Exception $e) {
            $this->addException($e);
        }

    }

}
 