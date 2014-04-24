<?php
/**
 *
 * User: migue
 * Date: 24/04/14
 * Time: 21:30
 */

namespace Huruk\Dispatcher;


use Huruk\Util\Singleton;

class ClosureStorage implements \ArrayAccess
{
    private $storage = array();
    use Singleton;


    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    public function offsetGet($offset)
    {
        return (isset($this[$offset])) ? $this->storage[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->storage[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this[$offset])) {
            unset($this->storage[$offset]);
        }
    }
}
