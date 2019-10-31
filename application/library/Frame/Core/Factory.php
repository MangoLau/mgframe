<?php
/**
 * @author MangoLau
 */

namespace Frame\Core;

use Frame\Exception\ModuleConfigException;

class Factory
{
    /**
     * load config
     * @param $module
     * @param $id
     * @return array|\Yaf\Config\Ini
     */
    private static function loadConfig($module, $id)
    {
        $config = [];
        $file = ROOT_PATH . "/config/{$module}.ini";
        if (!file_exists($file)) {
            return $config;
        }
        $config = new \Yaf\Config\Ini($file, \YAF\ENVIRON);
        $config = $config->toArray();
        if (!isset($config[$id])) {
            throw new ModuleConfigException("{$module} config: {$id} not set !");
        }
        return $config[$id];
    }

    /**
     * redis
     * @param $id
     * @return Redis
     */
    public static function redis($id)
    {
        return new Redis(self::loadConfig('redis', $id));
    }

    /**
     * log
     * @param $id
     * @return object
     */
    public static function log($id)
    {
        $config = self::loadConfig('log', $id);
        $driver = empty($config['driver']) ? 'EchoLog' : $config['driver'];
        $class  = 'Frame\\Core\\Log\\'.ucfirst($driver);
        return new $class($config);
    }

    /**
     * db
     * @param $id
     * @return object
     */
    public static function db($id)
    {
        $config = self::loadConfig('db', $id);
        $driver = empty($config['driver']) ? 'Mysql' : $config['driver'];
        $class = 'Frame\\Core\\Db\\' . ucfirst($driver);
        return new $class($config);
    }

    /**
     * call static
     * @param $name
     * @param $arguments
     * @return object $name
     */
    public static function __callStatic($name, $arguments)
    {
        $class = 'Frame\\Core\\' . ucfirst($name);
        $alias = empty($arguments[0]) ? 'default' : $arguments[0];
        return $class(self::loadConfig($name, $alias));
    }
}