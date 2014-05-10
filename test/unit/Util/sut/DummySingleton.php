<?php
/**
 *
 * User: migue
 * Date: 22/03/14
 * Time: 19:32
 */

namespace unit\src\Huruk\Util\sut;


use Huruk\Util\Singleton;

class DummySingleton
{
    private $value = null;
    use Singleton;

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
