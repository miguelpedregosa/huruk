<?php
/**
 *
 * User: migue
 * Date: 22/02/14
 * Time: 21:03
 */

namespace Huruk\Util;


use SplPriorityQueue;

class StablePriorityQueue extends SplPriorityQueue
{
    protected $serial = PHP_INT_MAX;

    public function insert($value, $priority)
    {
        parent::insert($value, array($priority, $this->serial--));
    }
}
 