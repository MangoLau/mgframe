<?php
/**
 * 登录接口
 * @author weicky
 *
 */
class LoginController extends Common_PublicController
{
    /**
     * 登录页面
     */
    public function indexAction()
    {
        $this->getView()->display('login/index.phtml');
    }

    /**
     * 登录的ajax处理
     */
    public function dologinAction()
    {
        $req = $this->getRequest();
        if ($req->isPost()) {
            $account  = $req->getPost('account');
            $password = $req->getPost('password');
            if (empty($account)) {
                return $this->json(102, '帐号不能为空');
            }
            if (empty($password)) {
                return $this->json(103, '密码不能为空');
            }
            $model = new Admin_AdminModel();
            $userInfo = $model->getByAccount($account);
            if (empty($userInfo)) {
                return $this->json(104, '用户名或密码不正确');
            }
            if (!password_verify($password, $userInfo['passwd'])) {
                return $this->json(105, '用户名或密码不正确');
            }
            if ($userInfo['status'] != 1) {
                return $this->json(106, '这管理帐号已禁用');
            }
            /*
            //取用户
            $roleModel = new RoleModel();
            $roleInfo = $roleModel->where('id', $userInfo['role_id'])->getOne();
            if(empty($roleInfo)) {
                return $this->json(107, '角色不存在');
            }
            if($roleInfo['status'] != 1) {
                return $this->json(108, '角色已禁用');
            }
            */
            //登录成功
            $uid = $userInfo['id'];
            unset($userInfo['passwd']);
            //取角色
            $roles = $this->getRoles($uid);
            //设置会话状态
            $sess = Yaf\Session::getInstance();
            $sess->uid = $uid;
            $sess->roles = $roles;
            $sess->name = empty($userInfo['name']) ? $userInfo['account'] : $userInfo['name'];
            $sess->account = $userInfo['account'];
            $sess->avatar = $userInfo['avatar'];
            $sess->lastip = $userInfo['last_ip'];
            $sess->lasttime = $userInfo['last_login_time'];
            //更新最后登录信息
            $ret = $model->updateLastLogin($uid);
            if ($ret) {
                return $this->json(1, '');
            } else {
                return $this->json(500, '');
            }
        }
        return false;
    }

    /**
     * 取角色列表
     * @param int $uid 用户ID
     * @return array 角色列表
     */
    private function getRoles($uid)
    {
        $modelAdminRole = new Admin_AdminroleModel();
        return $modelAdminRole->getRolesByUid($uid);
    }
}