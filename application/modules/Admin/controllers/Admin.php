<?php
/**
 * 管理员个人相关操作
 * @author weicky
 *
 */
class AdminController extends Admin_ProtectedController
{
    /**
     * 注销登录功能
     */
    public function logoutAction()
    {
        $sess = Yaf\Session::getInstance();
        $sess->del('uid');
        $sess->del('name');
        $sess->del('avatar');
        $sess->del('roleid');
        $this->redirect('/admin/login/index');
        return false;
    }

    /**
     * 修改密码接口
     */
    public function modifyPasswdAction()
    {
        $req = $this->getRequest();
        if ($req->isPost()) {
            $model = new AdminModel();
            $pwd = $req->getPost('newPwd');
            $ret = $model->updateField($this->sess->uid, 'passwd', $pwd);
            if ($ret) {
                return $this->json(1, '');
            } else {
                return $this->json(500, '');
            }
        }
        $vars = [
            'layout_title' => '修改密码',
            'layout_desc' => '修改您在管理后台的登录密码',
            'layout_script' => 'admin/admin/modifypasswd.js',
            'layout_links' => [
                ['name' => '首页', 'url' => '/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '修改密码', 'active' => 1]
            ],
        ];
        return $this->layout('modifypasswd', $vars);
    }
}