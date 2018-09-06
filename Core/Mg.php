<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/5
 * Time: 10:28
 */

namespace Core;

use Core\Lib\Route;

/**
 * 框架核心类
 * Class Mg
 * @package Core
 **/
class Mg
{

    public static function run()
    {
        $route = new Route();
        $ctrlClass = ucfirst(strtolower($route->ctrl));
        $action = $route->action;
        $ctrlFile = ROOT_PATH . DS . MODULE . '/controller/' . $ctrlClass . '.php';
        $ctrlClass = '\\' . MODULE . '\controller\\' . $ctrlClass;
        if (is_file($ctrlFile)) {
            include $ctrlFile;
            $ctrl = new $ctrlClass();
            $ctrl->$action();
        } else {
            throw new \Exception('invalid controller:'.$ctrlClass);
        }
    }
}