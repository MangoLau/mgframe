<?php
use \Api\Exception\NeedLoginException;

/**
 * 需要验证权限的控制器基类，所有需要登录后才能访问的页面或接口请继承本类
 * @author MangoLau
 */
class Common_ProtectedController extends Common_PublicController
{
    /**
     * 会话数据
     * @var array
     */
    protected $sess;

    /**
     * 是否必须登录
     * @var
     */
    protected $mustLogin = true;

	/**
	 * init
	 * @see Common_BaseController::init()
	 */
	public function init()
	{
		parent::init();
		//验证是否登录
		$this->checkLogin();
	}

	/**
	 * 检查登录状态
	 * @throws App\Exception\AuthException
	 */
	protected function checkLogin()
	{
	    if (PHP_SAPI == 'cli') {
	        return;
        }
	    $req = $this->getRequest();
	    $sess_id = $req->get('sess');
	    if (empty($sess_id)) {
	        if ($this->mustLogin) {
                throw new NeedLoginException('need login');
            }
        } else {
            $mdlSess = new SessionModel();
            $sess = $mdlSess->get($sess_id);
            if (!$sess) {
                if ($this->mustLogin) {
                    throw new NeedLoginException('session expired, login again');
                }
            } else {
                $this->sess = $sess;
                //更新会话有效期
                $mdlSess->touch($sess_id);
            }
        }
	}

	public function getUid()
    {
        return $this->sess ? $this->sess['uid'] : 0;
    }
}
