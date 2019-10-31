<?php
/**
 * @author MangoLau
 */

namespace Frame\Core;


use Frame\Exception\ModuleNotFoundException;

class MG
{
    /**
     * @var array
     */
    public static $modules = [
        'db'      => false,
        'log'     => true,
        'redis'   => false,
    ];

    /**
     * @var array
     */
    public static $objects = [];

    /**
     * init
     */
    public static function init()
    {
        foreach (self::$modules as $k => $v) {
            if ($v) self::loadModule($k);
        }
    }

    /**
     * load module
     * @param string $module
     * @param string $id
     * @return mixed
     */
    public static function loadModule(string $module, string $id = 'default')
    {
        $key = $module . '_' . $id;
        if (!empty(self::$objects[$key])) {
            return self::$objects[$key];
        }
        if (!isset(self::$modules[$module])) {
            throw new ModuleNotFoundException("{$module} not found!");
        }
        self::$objects[$key] = Factory::$module($id);
        return self::$objects[$key];
    }

    /**
     * call static
     * @param $name
     * @param $arguments
     * @return object $name
     */
    public static function __callStatic($name, $arguments)
    {
        $id = 'default';
        if (!empty($arguments) && is_string($arguments[0])) {
            $id = $arguments[0];
        }
        return self::loadModule($name, $id);
    }

    /**
     * shutdown
     */
    public static function shutdown()
    {
        foreach (self::$objects as $k => &$object) {
            unset($object);
        }
        self::$objects = null;
    }
}