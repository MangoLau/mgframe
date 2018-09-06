<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/5
 * Time: 14:24
 */

namespace Core\Lib;

/**
 * 配置类
 * Class Conf
 * @package Core\Lib
 */
class Conf
{

    public static $conf = [];

    /**
     * get one config
     * @param $name
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    public static function get($name, $file)
    {
        //1.判断文件是否存在
        //2.判断对应配置是否存在
        //3.缓存配置
        if (isset(self::$conf[$file][$name])) {
            return self::$conf[$file][$name];
        } else {
            $path = ROOT_PATH . DS . 'conf' . DS . $file . '.php';
            if (is_file($path)) {
                $conf = include $path;
                if (isset($conf[$name])) {
                    self::$conf[$file] = $conf;
                    return $conf[$name];
                } else {
                    throw new \Exception('no config:' . $conf . ':' .$name);
                }
            } else {
                throw new \Exception('no config file:' . $file);
            }
        }
    }

    /**
     * get one struct all config
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    public static function all($file)
    {
        if (isset(self::$conf[$file])) {
            return self::$conf[$file];
        } else {
            $path = ROOT_PATH . DS . 'conf' . DS . $file . '.php';
            if (is_file($path)) {
                $conf = include $path;
                self::$conf[$file] = $conf;
                return $conf;
            } else {
                throw new \Exception('no config file:' . $file);
            }
        }
    }
}