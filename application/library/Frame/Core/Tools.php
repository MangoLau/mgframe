<?php

namespace Frame\Core;

/**
 * Tools
 * @author look
 */
class Tools
{
    
    /**
     * set proecess name
     *
     * @param string $name
     */
    static function setProcessName($name)
    {
        @cli_set_process_title($name);
    }

    /**
	 * curl
	 *
	 * @param string $uri
	 * @param string $method
	 * @param mixed $payload
	 * @param array $header
     * @param array $otherOptions
	 * @return $mixed
	 */
	static function curl($uri, $method='GET', $payload=null, $header=[], $otherOptions=[], &$errno=false, &$error=false, &$httpCode=false)
	{
		$ch = curl_init();
		$method  = strtoupper($method);
		$payload = is_scalar($payload) ? $payload : http_build_query($payload);
		if (!empty($payload) && $method == 'GET') {
            $uri .= strpos($uri, '?') === false ? '?'.$payload : '&'.$payload;
        }
		$options = [
			CURLOPT_TIMEOUT         =>  3,
			CURLOPT_MAXREDIRS       =>  3,
	    	CURLOPT_CONNECTTIMEOUT  =>  3,
	    	CURLOPT_SSL_VERIFYPEER  =>  false,
	    	CURLOPT_RETURNTRANSFER  =>  true,    	
			CURLOPT_FOLLOWLOCATION  =>  true,
	    	CURLOPT_URL             =>  $uri,
	    	CURLOPT_CUSTOMREQUEST   =>  $method
		];
		if (!empty($payload) && $method == 'POST') {
    		$options[CURLOPT_POSTFIELDS] = $payload;
    	}
    	if (!empty($header)) {
    		$options[CURLOPT_HTTPHEADER] = $header;
    	}
    	if (!empty($otherOptions)) {
    	    foreach ($otherOptions as $k => $v) {
    	        $options[$k] = $v;
            }
        }
    	curl_setopt_array($ch, $options);
    	$resp = curl_exec($ch);
        if($resp === false) {
            if($error !== false) {
                $error = curl_error($ch);
            }
            if($errno !== false) {
                $errno = curl_errno($ch);
            }
        }
        if($httpCode !== false) {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
    	curl_close($ch);
    	return $resp;
	}

    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded',
    );

    /**
     * 发送HTTP状态
     * @param integer $code 状态码
     * @return string 状态描述字符串
     */
    public static function httpStatus($code) {
        if(isset(self::$_status[$code])) {
            return;
        }
        $txt = self::$_status[$code];
        header("Status: {$code} {$txt}");
        header("HTTP/1.1 {$code} {$txt}");
        echo self::$_status[$code];
    }
}