<?php

/**
 * Security
 * @author MangoLau
 */
class Security
{
		
	const XSRF_PARAM  = '_xsrf';
	const XSRF_HEADER = 'XSRF';

    /**
     * gen rand string
     *
     * @param int $length
     * @return string
     * @throws Exception
     */
	static function genRandString($length = 4)
	{
		$rand = random_bytes($length);
		return bin2hex($rand);
	}

	/**
	 * get xsrf token
	 *
	 * @return string
	 */
	static function getXsrfToken()
	{
		return $_COOKIE[static::XSRF_PARAM];
	}

    /**
     * genXsrfToken
     *
     * @return string
     * @throws Exception
     */
	static function genXsrfToken()
	{	
		$token = self::getXsrfToken();
		if (empty($token)) {
			$token = self::genRandString(4);
			$_COOKIE[static::XSRF_PARAM] = $token;
		}
		setcookie(static::XSRF_PARAM, $token, time()+86400, '/', '', false, true);
		return $token;
	}

    /**
     * check
     *
     * @return boolean
     * @throws Exception
     */
	static function check()
	{
		$token = self::genXsrfToken();
		$req = Yaf\Dispatcher::getInstance()->getRequest();
		if ($req->isGet()) {
			return true;
		}
		if ($req->isXmlHttpRequest()) {
			$key = 'HTTP_'.static::XSRF_HEADER;
			return 0 == strcmp($req->getServer($key), $token);
		}
		return 0 == strcmp($req->getPost(static::XSRF_PARAM), $token);
	}
}