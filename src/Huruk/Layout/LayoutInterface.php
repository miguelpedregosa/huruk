<?php
/**
 *
 * User: migue
 * Date: 9/02/14
 * Time: 17:03
 */

namespace Huruk\Layout;


interface LayoutInterface
{
    /**
     * Renderiza el layout
     * @param $contents
     * @return string
     */
    public function render($contents);
} 