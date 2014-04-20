<?php
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
