<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/5
 * Time: 14:09
 */

namespace Core\Lib;

use Core\Lib\Conf;

class Route
{
    public $ctrl;
    public $action;

    /**
     * 1.隐藏index.php
     * 2.获取URL参数部分
     * 3.返回对应控制器和方法
     * Route constructor.
     */
    public function __construct()
    {
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            //解析index/index
            $path = $_SERVER['REQUEST_URI'];
            $pathArr = explode('/', trim($path, '/'));
            if (isset($pathArr[0])) {
                $this->ctrl = $pathArr[0];
                unset($pathArr[0]);
            }
            if (isset($pathArr[1])) {
                $this->action = $pathArr[1];
                unset($pathArr[1]);
            } else {
                $this->action = Conf::get('DEFAULT_ACTION', 'route');
            }
            //URI多余部分转换成GET
            //id/1/str/2
            $count = count($pathArr) + 2;
            $i = 2;
            while ($i < $count) {
                if (isset($pathArr[$i + 1])) {
                    $_GET[$pathArr[$i]] = $pathArr[$i + 1];
                }
                $i = $i + 2;
            }
        } else {
            $this->ctrl = Conf::get('DEFAULT_CTRL', 'route');
            $this->action = Conf::get('DEFAULT_ACTION', 'route');
        }
    }
}