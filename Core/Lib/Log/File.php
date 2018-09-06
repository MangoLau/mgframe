<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/6
 * Time: 10:02
 */

namespace Core\Lib\Log;

use Core\Lib\Conf;

class File
{

    /**
     * log
     *
     * @param $level
     * @param $message
     * @param array $context
     * @return bool
     * @throws \Exception
     */
    public function log($level, $message, array $context = array())
    {
        $dir = Conf::get('DIR', 'log');
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777)) {
                throw new \Exception('create log dir error');
            }
        }
        $dir .= date('Y/m') . '/';
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new \Exception('create log dir error');
            }
        }
        $message = $this->dataFormat($level, $message, $context);
        $file = $dir . date('d') . '.php';
        return error_log($message, 3, $file);
    }

    /**
     * format log message
     *
     * @param $level
     * @param $message
     * @param array $context
     * @return string
     */
    public function dataFormat($level, $message, array $context = [])
    {
        $dateFormat = Conf::get('DATE_FORMAT', 'log');
        $date = date($dateFormat);
        if (!empty($context)) {
            $message = $this->interpolate($message, $context);
        }
        $format = "[%s] [%s] %s" . PHP_EOL;// 时间，日志等级，message
        return sprintf($format, $date, $level, $message);
    }

    /**
     * 用上下文信息替换记录信息中的占位符
     *
     * @param $message
     * @param array $context
     * @return string
     */
    public function interpolate($message, array $context = array())
    {
        // 构建一个花括号包含的键名的替换数组
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        // 替换记录信息中的占位符，最后返回修改后的记录信息。
        return strtr($message, $replace);
    }
}