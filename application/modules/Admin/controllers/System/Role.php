<?php
/**
 * Role
 * @author look
 */
class System_RoleController extends Admin_ProtectedController
{   	
	/**
	 * index
	 */
    public function indexAction()
    {
	    $vars = [
	        'layout_title' => '角色管理',
	        'layout_desc' => '添加、删除管理员的角色',
	        'layout_links' => [
	            ['name' => '首页', 'url' => '/index/index', 'icon'  => 'fa-dashboard'],
	            ['name' => '角色管理', 'active' => 1]
	        ],
	        'layout_css' => [
	            'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
	        ],
	        'layout_script' => [
	            'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
	            'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
	            'admin/system/role.js',
	        ],
	    ];
	    return $this->layout('index', $vars);
    }

    /**
     * 列表
     */
    public function listAction()
    {
        $model = new Admin_RoleModel();
        $ret = $model->getAll();
        return $this->json(0, '', $ret);
    }

    /**
     * 添加
     * @return boolean
     */
    public function addAction()
    {
        $req = $this->getRequest();
        $name = $req->getPost('name');
        if (empty($name)) {
            return $this->json(500, '角色名不能为空！');
        }
        $model = new Admin_RoleModel();
        $ret = $model->simpleAdd($name);
        return $this->json(0, '', '');
    }

    /**
     * 删除
     * @return boolean
     */
    public function delAction()
    {
        $req = $this->getRequest();
        $id = intval($req->getPost('id'));
        if (!$id) {
            return $this->json(500, '角色ID为空！');
        } elseif ($id == 1) {
            return $this->json(500, '不能删除超级管理员！');
        }
        $model = new Admin_RoleModel();
        $ret = $model->del($id);
        return $this->json(0, '', '');
    }

    /**
     * 修改名称
     */
    public function updateNameAction()
    {
        $req = $this->getRequest();
        $id = intval($req->getPost('id'));
        $name = $req->getPost('name');
        if (!$id) {
           return $this->json(500, '请选择要修改的记录！'); 
        } elseif(empty($name)) {
            return $this->json(500, '名称不能为空！');
        }
        $model = new Admin_RoleModel();
        $flag = $model->changeName($id, $name);
        if ($flag) {
            return $this->json(0, '');
        } else {
            return $this->json(500, '操作失败，请重试！');
        }
    }

    /**
     * 启用
     * @return boolean
     */
    public function enableAction()
    {
        $req = $this->getRequest();
        $id = intval($req->getPost('id'));
        if (!$id) {
            return $this->json(500, '角色ID为空！');
        }
        $model = new Admin_RoleModel();
        $ret = $model->setStatus($id, 1);
        return $this->json(0, '', '');
    }
 
    /**
     * 禁用
     * @return boolean
     */
    public function disableAction()
    {
        $req = $this->getRequest();
        $id = intval($req->getPost('id'));
        if (!$id) {
            return $this->json(500, '角色ID为空！');
        } elseif ($id == 1) {
            return $this->json(500, '不能禁用超级管理员！');
        }
        $model = new Admin_RoleModel();
        $ret = $model->setStatus($id, 0);
        return $this->json(0, '', '');
    }
}