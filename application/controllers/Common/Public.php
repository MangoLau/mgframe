<?php
/**
 * 公开控制器方法的基类，所有不需要登录的页面或接口请直接继承本类
 * @author MangoLau
 */
class Common_PublicController extends \Yaf\Controller_Abstract
{
	/**
	 * @var boolean
	 */
	public $isAjax;
	
	/**
	 * init
	 */
	public function init()
	{
		$req = $this->getRequest();
		$this->isAjax = $req->isXmlHttpRequest();
		$this->yafAutoRender = false;
        if (!$this->isAjax) {
            $this->initAdminViewVars($req->getServer('HTTP_HOST'));
        }
	}

    /**
     * 初始化管理后台的模板变量
     * @param $host
     */
	protected function initAdminViewVars($host)
    {
        $config = Yaf\Registry::get('config');
        if ($host != $config->admin->domain) {
            return;
        }
        $view = $this->getView();
        $view->assign('YafEnviron', \Yaf\Application::app()->environ());
        $view->assign('webRoot', $config->admin->domain);
        $view->assign('resourcesUri', $config->admin->resources_uri);
    }

    /**
     * json
     * @param $code
     * @param $msg
     * @param null $data
     * @return bool
     */
	public function json($code, $msg, $data = null)
	{
	    $res = [];
		$res['code'] = intval($code);
		$res['msg']  = $msg;
		if ($data !== null) {
			$res['data'] = $data;
		}
		$resp = $this->getResponse();
		if (PHP_SAPI != 'cli') {
            $resp->setHeader('Content-Type', 'application/json; charset=utf-8');
        }
		$resp->setBody(json_encode($res));
		return false;
	}

    /**
     * 按布局输出视图方法
     */
    public function layout($tpl, $vars=[], $layout='default')
    {
        $ctrl = strtolower($this->_name) ?: 'index';
        $cfg = Yaf\Registry::get('config');
        $ext = $cfg->application->view->ext ?: 'phtml';
        $vars['layout_inc'] = str_replace('_', '/', $ctrl) . "/{$tpl}.{$ext}";
        $this->getView()->display("layout/layout-{$layout}.{$ext}", $vars);
        return false;
	}
}