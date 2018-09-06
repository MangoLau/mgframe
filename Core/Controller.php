<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/6
 * Time: 15:44
 */

namespace Core;


class Controller
{

    public $assign = [];
    const DEFAULT_TYPE = 'json';

    public function assign($name, $value)
    {
        $this->assign[$name] = $value;
    }

    public function display($fileName)
    {
        $dir = ROOT_PATH . DS . MODULE . DS . 'views';
        $loader = new \Twig_Loader_Filesystem($dir);
        $twig = new \Twig_Environment($loader, [
            'cache' => ROOT_PATH . DS . 'log/twig',
            'debug' => DEBUG,
        ]);
        echo $twig->render($fileName, $this->assign);
    }

    /**
     * jump
     * @param $url
     */
    public function jump($url)
    {
        header('location:'.$url);
    }
}