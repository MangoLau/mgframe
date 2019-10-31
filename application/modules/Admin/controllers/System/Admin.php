<?php
/**
 * Admin
 * @author look
 */
class System_AdminController extends Admin_ProtectedController
{
	/**
	 * index
	 */
    public function indexAction()
    {
    	$model = new Admin_RoleModel();
    	$roleList = $model->getList();  
		$this->getView()->assign('roleList', $roleList);
		$vars = [
		    'layout_title' => '管理员账号管理',
		    'layout_desc' => '添加、删除、修改管理员账号',
		    'layout_links' => [
		        ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
		        ['name' => '管理员账号管理', 'active' => 1]
		    ],
		    'layout_css' => [
		        'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
		    ],
		    'layout_script' => [
		        'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
		        'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
		        'admin/system/admin.js',
		    ],
		    'layout_js_vars' => [
		        'roles' => $roleList,
		    ],
		];
		return $this->layout('index', $vars);
    }

    /**
     * 添加账号
     */
    public function addAction()
    {
        $req = $this->getRequest();
        $account = $req->getPost('account');
        $name = $req->getPost('name');
        $passwd = $req->getPost('passwd');
        $roles = $req->getPost('roles');
        if (empty($account)) {
            return $this->json(500, '账号不能为空！');
        } elseif (preg_match('/\W/', $account)) {
            return $this->json(500, '账号格式不正确！');
        } elseif (empty($name)) {
            return $this->json(500, '名称不能为空！');
        } elseif (empty($passwd)) {
            return $this->json(500, '初始密码不能为空！');
        } elseif (!is_array($roles) || empty($roles)) {
            return $this->json(500, '请至少选择一个角色！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $uid = $mdlAdmin->add($account, $name, $passwd);
        if (!$uid) {
            return $this->json(500, '操作失败，请重试!');
        }
        $mdlAdminRole = new Admin_AdminroleModel();
        foreach ($roles as $roleid) {
            $mdlAdminRole->add($uid, $roleid);
        }
        return $this->json(0, '');
    }

    /**
     * 删除账号
     */
    public function delAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        if (!$uid) {
            return $this->json(500, '请指定要删除的账号！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $flag = $mdlAdmin->del($uid);
        if ($flag) {
            $this->json(0, '');
        } else {
            $this->json(500, '操作失败，请重试！');
        }
    }

    /**
     * 启用账号
     */
    public function enableAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        if (!$uid) {
            return $this->json(500, '请指定要启用的账号！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $flag = $mdlAdmin->updateField($uid, 'status', 1);
        if ($flag) {
            $this->json(0, '');
        } else {
            $this->json(500, '操作失败，请重试！');
        }
    }

    /**
     * 禁用账号
     */
    public function disableAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        if (!$uid) {
            return $this->json(500, '请指定要禁用的账号！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $flag = $mdlAdmin->updateField($uid, 'status', 0);
        if ($flag) {
            $this->json(0, '');
        } else {
            $this->json(500, '操作失败，请重试！');
        }
    }

    /**
     * 修改名称
     */
    public function updateNameAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        $name = $req->getPost('name');
        if (!$uid) {
            return $this->json(500, '请指定要修改的账号！');
        }
        if (empty($name)) {
            return $this->json(500, '名称不能为空！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $flag = $mdlAdmin->updateField($uid, 'name', htmlspecialchars($name));
        if ($flag) {
            $this->json(0, '');
        } else {
            $this->json(500, '操作失败，请重试！');
        }
    }
    
    /**
     * 修改密码
     */
    public function updatePasswdAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        $passwd = $req->getPost('passwd');
        if (!$uid) {
            return $this->json(500, '请指定要修改的账号！');
        }
        if (empty($passwd)) {
            return $this->json(500, '密码不能为空！');
        }
        if (strlen($passwd) < 6) {
            return $this->json(500, '密码长度不能小于6！');
        }
        $mdlAdmin = new Admin_AdminModel();
        $flag = $mdlAdmin->updateField($uid, 'passwd', $passwd);
        if ($flag) {
            $this->json(0, '');
        } else {
            $this->json(500, '操作失败，请重试！');
        }
    }

    /**
     * 更新角色
     */
    public function updateRolesAction()
    {
        $req = $this->getRequest();
        $uid = intval($req->getPost('uid'));
        $roles = $req->getPost('roles');
        if (!$uid) {
            return $this->json(500, '请指定要修改的账号！');
        }
        if (!is_array($roles) || empty($roles)) {
            return $this->json(500, '请至少指定一个角色！');
        }
        $mdlAdminRole = new Admin_AdminroleModel();
        $flag = $mdlAdminRole->delByUid($uid);
        if ($flag === false) {
            return $this->json(500, '清除旧数据时失败，请重试！');
        }
        foreach ($roles as $roleid) {
            $flag = $mdlAdminRole->add($uid, $roleid);
            if ($flag === false) {
                return $this->json(500, '操作失败，请重试！');
            }
        }
        return $this->json(0, '');
    }

    /**
     * 账号列表
     */
    public function listAction()
    {
        $mdlAdmin = new Admin_AdminModel();
        $accounts = $mdlAdmin->getList();
        if (!empty($accounts)) {
            $ids = array_column($accounts, 'id');
            //取角色列表
            $mdlAdminRole = new Admin_AdminroleModel();
            $roles = $mdlAdminRole->getRolesByUids($ids);
            //给每个账号填充角色数据
            foreach ($accounts as &$item) {
                $item['roles'] = isset($roles[$item['id']]) ? $roles[$item['id']] : [];
            }
        }
        $this->json(0, '', $accounts);
    }
}