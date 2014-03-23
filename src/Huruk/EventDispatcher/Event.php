<?php
/**
 * Created by PhpStorm.
 * User: migue
 * Date: 24/11/13
 * Time: 20:43
 */

namespace Huruk\EventDispatcher;


use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Represent an event
 * Class Event
 * @package Huruk\EventDispatcher
 */
class Event extends SymfonyEvent implements \ArrayAccess
{
    const EVENT_RUN = 'event.run';
    const EVENT_RUN_EXCEPTION = 'event.run.exception';
    const EVENT_RUN_NOT_FOUND_EXCEPTION = 'event.run.not_found_exception';
    const EVENT_ROUTE_MATCH = 'event.route.match';
    const EVENT_INVALID_CONTROLLER_CLASS = 'event.controller.invalid_class';
    const EVENT_INVALID_ACTION_NAME = 'event.controller.invalid_action';
    const EVENT_PREACTION = 'event.controller.preaction';
    const EVENT_POSTACTION = 'event.controller.postaction';

    private $data;

    /**
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * Factoria estática
     * @param array $data
     * @return Event
     */
    public static function make($data = array())
    {
        return new self($data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data = array())
    {
        $this->data = $data;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return is_array($this->data) && isset($this->data[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return (is_array($this->data) && isset($this[$offset])) ? $this->data[$offset] : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_array($this->data)) {
            $this->data[$offset] = $value;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (is_array($this->data) && isset($this[$offset])) {
            unset($this->data[$offset]);
        }
    }
}
