<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/5
 * Time: 17:15
 */

namespace Core;

use Psr\Log\AbstractLogger;
use Core\Lib\Conf;


class Log extends AbstractLogger
{

    public static $class;

    public static function init()
    {
        //确定存储方式
        $drive = Conf::get('DRIVER', 'log');
        $class = '\Core\Lib\Log\\' . $drive;
        self::$class = new $class();
    }

    /**
     * log
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function log($level, $message, array $context = array())
    {
        // Implement log() method.
        return self::$class->log($level, $message, $context);
    }
}