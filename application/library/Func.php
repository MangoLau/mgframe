<?php

/**
 * Func
 * @author MangoLau
 */
class Func
{
    /**
     * tree
     *
     * list : array(1=>array('id'=>1, 'pid'=>0, 'title'=>'x'),...)
     * @param array $list
     * @return array
     */
    static function tree(&$list = array())
    {
        $tree = array();
        if (!empty($list)) {
            foreach ($list as $item) {
                if (isset($list[$item['pid']])) {
                    $list[$item['pid']]['children'][] = &$list[$item['id']];
                } else {
                    $tree[] = &$list[$item['id']];
                }
            }
        }
        return $tree;
    }

    /**
     * js unescape
     *
     * @param string $str
     * @return Ambigous <string, unknown>
     */
    static function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f) {
                    $ret .= chr($val);
                } elseif ($val < 0x800) {
                    $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
                } else {
                    $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
                }
                $i += 5;
            } elseif ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }
        }
        return $ret;
    }


    /**
     * xss filter
     *
     * @param mixed $data (string or array)
     * @return mixed
     */
    static function xssFilter($data)
    {
        if (is_numeric($data)) {
            return $data;
        }
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = self::xssFilter($v);
            }
            return $data;
        }
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Remove style expression behaviour
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            // Remove unwanted tags
            // -- disabled iframe for i(?:layer)
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:layer|frame)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        return $data;
    }

    /**
     * html
     *
     * @param string $str
     * @param bool $doubleEncode
     * @return string
     */
    static function html($str, $doubleEncode = TRUE)
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * get client ip
     *
     * @return string
     */
    static function getClientIp()
    {
        $keys = array(
            'http_x_real_ip',
            'http_client_ip',
            'http_x_forwarded_for',
            'remote_addr'
        );
        $req = Yaf\Dispatcher::getInstance()->getRequest();
        foreach ($keys as $k) {
            $ip = $req->getServer($k);
            if (!empty($ip) && strcasecmp($ip, 'unkonw') != 0) {
                return $ip;
            }
        }
        return '';
    }

    /**
     * get img
     *
     * @param string $path
     * @return string
     */
    static function getImg($path)
    {
        $config = Yaf\Registry::get('config');
        return $config->site->static . $path;
// 		$domain = $config->site->domain;
// 		return 'http://s'.rand(2, 7).'.'.$domain.$path;
    }

    /**
     * is chinese
     */
    static function isChinese($str)
    {
        return preg_match("/([\x{4e00}-\x{9fa5}]+)/iu", $str);
    }

    /**
     * is dev
     */
    static function isDev()
    {
        return strtolower(\YAF\ENVIRON) == 'dev';
    }

    /**
     * std log
     *
     * @param string $msg ,
     * @param string $level
     */
    static function stdlog($msg, $level = 'INFO')
    {
        echo "[$level] " . date('Y-m-d H:i:s') . " $msg\n";
    }

    /**
     * 获取短信验证码
     */
    static function get_sms_code()
    {
        return mt_rand(1000, 9999);
    }

    /**
     * 验证手机号是否正确
     * @param $mobile
     * @return bool
     */
    static function isMobile($mobile)
    {
//        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,3,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
        return preg_match('#^1[\d]{10}$#', $mobile) ? true : false;
    }

    /**
     * 截取字符串
     * @param string $str 要截取的字符串
     * @param int $num 截取的大小
     * @param string $tail 要拼接的尾部
     * @return string
     */
    static function subStr($str, $num = 30, $tail = '')
    {
        if (mb_strlen($str) > $num) {
            return mb_substr($str, 0, $num) . $tail;
        } else {
            return $str;
        }
    }

    /**
     * 获取配置
     * @param $module
     * @param $id
     * @return array|\Yaf\Config\Ini
     */
    static function config($module, $id=null)
    {
        $config = [];
        $file = ROOT_PATH . '/config/' . $module . '.ini';
        if (!file_exists($file)) {
            return $config;
        }
        $config = new \Yaf\Config\Ini($file, \YAF\ENVIRON);
        $config = $config->toArray();
        if ($id === null) {
            return $config;
        }
        if (!isset($config[$id])) {
            return null;
        }
        return $config[$id];
    }

    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}