<?php
/**
 * @author MangoLau
 */

namespace Frame\Core\Log;

use Func;
use Psr\Log\AbstractLogger;

class FileLog extends AbstractLogger
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
        $config = Func::config('log', 'default');
        $dir = $config['path'];
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
        $config = Func::config('log', 'default');
        $date = date($config['date_format']);
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