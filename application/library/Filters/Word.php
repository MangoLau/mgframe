<?php
 /**
  * 敏感词过滤类
  * @author JasyDong
  *
  */

namespace Filters;

class Word {

	public static $keyword = array();

    /**
     * 从文件中加载敏感词
     * @param $filename
     * @return array
     */
    static function getBadWords($filename='') {
        if(!$filename) {
            $filename = __DIR__.'/sensitive_words.txt';
        }
        $file_handle = fopen($filename, "r");
        while (!feof($file_handle)) {
            $line = trim(fgets($file_handle));
            array_push(self::$keyword, $line);
        }
        fclose($file_handle);
        return self::$keyword;
    }

    /**
     * 替换字符串里面的敏感词
     * @param string $content 要处理的内容
     * @param string $target 替换成的内容
     * @param string $filename 敏感词库
     * @return string
     */
    static function filterContent($content, $target='***', $filename='') {
        $badwords = self::getBadWords($filename);
		$content = str_replace(array("\r", "\n", "\\"), "", $content);
        return strtr($content, array_combine($badwords, array_fill(0, count($badwords), $target)));
    }

    /**
     * 判断字符串里面是否有敏感词
     * @param string $content 要判断的内容
     * @param string $filename 敏感词库
     * @return bool
     */
    static function isContainSensitiveWords($content, $filename='') {
        $badwords = self::getBadWords($filename);
        $content = str_replace(array("\r", "\n", "\\"), "", $content);
        $flag = false;
        foreach($badwords as $badword) {
            if(mb_strpos($content, $badword) !== false) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }
}
