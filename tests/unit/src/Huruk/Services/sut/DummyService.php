<?php
/**
 * User: migue
 * Date: 6/04/14
 * Time: 14:50
 */

namespace unit\src\Huruk\Services\sut;


class DummyService
{
    private $value = null;

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
