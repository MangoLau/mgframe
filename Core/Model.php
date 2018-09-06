<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/6
 * Time: 11:01
 */

namespace Core;

use Core\Lib\Conf;
use \Medoo\Medoo;

class Model extends Medoo
{

    public function __construct()
    {
        $options = Conf::all('database');
        parent::__construct($options);
    }
}