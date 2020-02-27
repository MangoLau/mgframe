<?php
/**
 * 需要验证权限的控制器基类，所有需要登录后才能访问的页面或接口请继承本类
 */
class Admin_ProtectedController extends Common_PublicController
{
    /**
     * 会话数据
     * @var array
     */
    protected $sess;

    /**
     * 可访问的模块ID列表
     * @var array
     */
    protected $allowedMenus;

    /**
     * 是否超级管理员
     * @var bool
     */
    protected $supeUser;

    /**
     * init
     * @see Common_BaseController::init()
     */
    public function init()
    {
        parent::init();
        //验证是否登录
        $this->checkLogin();
        //加载可访问的模块列表
        $this->loadAllowedMenus();
        //验证是否有权限访问该菜单或接口
        $this->checkRule();
        //加载菜单
        if (!$this->isAjax) {
            $this->loadMenus();
        }
    }

    /**
     * 检查登录状态
     * @throws \Api\Exception\NeedLoginException
     */
    protected function checkLogin()
    {
        $this->sess = Yaf\Session::getInstance();
        if (!$this->sess->uid) {
            header("Location: /admin/login/index");
            throw new \Api\Exception\NeedLoginException('need login');
        }
        //判断是否超管
        if (isset($this->sess->roles[1])) {
            $this->supeUser = true;
        }
        //把会话数据传给模板
        if (!$this->isAjax) {
            $this->getView()->assign([
                'sess' => $this->sess,
                'superUser' => $this->supeUser,
            ]);
        }
        return true;
    }

    /**
     * 加载用户可访问的模块列表
     */
    protected function loadAllowedMenus()
    {
        $mdlMenuRole = new Admin_MenuRoleModel();
        $this->allowedMenus = $mdlMenuRole->getMenusByRoles(array_keys($this->sess['roles']));
    }

    /**
     * 取菜单列表
     * $roles 角色列表
     * 菜单列表
     */
    protected function loadMenus()
    {
        $roles = array_keys($this->sess['roles']);
        if (empty($roles)) {
            return;
        }
        $menus = [];
        $mdlMenus = new Admin_MenusModel();
        $menuRows = $mdlMenus->getListByIds($this->allowedMenus);
        if (!$menuRows) {
            return;
        }
        //两次遍历，避免出现父菜单在后不能正常展示的情况
        $req = $this->getRequest();
        $current_uri = strtolower('/admin/'.$req->controller.'/'.$req->action);
        foreach ($menuRows as $key => $item) {
            if ($item['parent_id']) {
                continue;
            }
            $item['open'] = false;
            $item['active'] = (strtolower($item['index_url']) == $current_uri);
            $item['menus'] = [];
            $menus[$item['menuid']] = $item;
            unset($menuRows[$key]);
        }
        foreach ($menuRows as $item) {
            if (!isset($menus[$item['parent_id']])) {
                continue;
            }
            $item['open'] = false;
            $item['active'] = (strtolower($item['index_url']) == $current_uri);
            if ($item['active']) {
                $menus[$item['parent_id']]['open'] = true;
            }
            $menus[$item['parent_id']]['menus'][] = $item;
        }
        $menus = array_values($menus);
        $this->getView()->assign('layout_menus', $menus);
    }

    /**
     * checkRule
     * @throws \Exception
     */
    public function checkRule()
    {
        $req = $this->getRequest();
        $uri = strtolower('/admin/'.$req->controller.'/'.$req->action);
        $uriWild = strtolower('/admin/'.$req->controller.'/*');
        $mdlMenuUrl = new Admin_MenuurlModel();
        $menuIds = $mdlMenuUrl->getMenusByUrls([$uri, $uriWild]);
        if ($menuIds) {
            $cross = array_intersect($this->allowedMenus, $menuIds);
            if (empty($cross)) {
                throw new \Api\Exception\AccessDeniedException('access denied!', 403);
            }
        }
    }

    /**
     * get admin_id
     * @return mixed
     */
    public function getUid()
    {
        $this->sess = Yaf\Session::getInstance();
        return $this->sess->uid;
    }
}
