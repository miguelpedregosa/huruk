<?php
/**
 * 
 * User: migue
 * Date: 22/02/14
 * Time: 22:29
 */

namespace Huruk\Layout;

/**
 * Layout for create Json documents
 * Class JsonLayout
 * @package Huruk\Layout
 */
class JsonLayout implements LayoutInterface
{

    /**
     * Renderiza el layout
     * @param $contents
     * @return string
     */
    public function render($contents)
    {
        return json_encode($contents);
    }
}
