<?php
/**
 * Trait que implementa el patron Singleton
 * User: migue
 * Date: 9/02/14
 * Time: 15:13
 */

namespace Huruk\Util;


trait Singleton
{
    private static $instance;

    /**
     * Constructor privado
     */
    private function __construct()
    {

    }

    /**
     * Devuelve la unica instancia permitida de la clase
     * @return Singleton|*
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Clone no permitido
     */
    public function __clone()
    {
        trigger_error('Clone not allowed.', E_USER_ERROR);
    }
}
