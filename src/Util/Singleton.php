<?php
namespace Huruk\Util;

/**
 * Trait que implementa el patron Singleton
 * Class Singleton
 * @package Huruk\Util
 */
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
     * @param $freshInstance
     * @return Singleton|*
     */
    public static function getInstance($freshInstance = false)
    {
        if (!self::$instance || $freshInstance) {
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
