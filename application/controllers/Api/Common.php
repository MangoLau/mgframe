<?php

/**
 * Api Common
 * @author MangoLau
 */
class Api_CommonController extends \Yaf\Controller_Abstract
{	
	/**
	 * init
	 */
	public function init()
	{
		$this->yafAutoRender = false;
	}

	/**
	 * redirect
	 *
	 * @param string $url
	 * @param int $status
	 */
	public function redirect($url, $status = 302)
	{
        $this->getResponse()->setRedirect($url);
	}

	/**
	 * header
	 *
	 * @param string $k
	 * @param string $v
	 */
	public function header($k, $v)
	{
        $resp = $this->getResponse();
        $resp->setHeader($k, $v);
	}

    /**
     * json
     * @param $code
     * @param string $msg
     * @param null $data
     * @return bool
     */
	public function json($code, $msg='', $data=null)
	{
	    $req = $this->getRequest();
        $ret = [
            'code' => intval($code),
            'msg' => $msg,
        ];
        if ($data !== null) {
            $ret['data'] = $data;
        }
        $cb = $req->getQuery('_callback');
        if (empty($cb)) {
            $this->header('Content-type', 'application/json');
            $body = json_encode($ret);
        } else {
            $this->header('Content-type', 'application/x-javascript');
            $body = "{$cb}(" . json_encode($ret) . ");";
        }
        $this->getResponse()->setBody($body);
		return false;
	}
}