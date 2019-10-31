<?php
/**
 * @author MangoLau
 */

class Admin_RoleModel extends \Frame\Core\Dao
{
    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'role';
        parent::__construct($table, $dbalias);
    }

    /**
     * 简单添加
     * @param string $name
     * @return boolean
     * @throws
     */
    public function simpleAdd($name)
    {
        $now = date('Y-m-d H:i:s');
        return $this->insert(['name' => htmlspecialchars($name), 'status' => 1, 'create_time' => $now, 'update_time' => $now]);
    }

    /**
     * 取列表
     */
    public function getAll()
    {
        return $this->fields('*')->select();
    }

    /**
     * 删除操作
     * @param int $id
     * @return boolean
     * @throws
     */
    public function del($id)
    {
        if ($id < 2) { //超级管理员角色也不能删除
            return false;
        }
        $flag = $this->where('id', $id)->delete();
        if (!$flag) {
            return false;
        }
        //同时删除该角色的账号映射和菜单映射
        $mdlAdminRole = new Admin_AdminRoleModel();
        $mdlAdminRole->delByRole($id);
        $mdlMenuRole = new Admin_MenuRoleModel();
        $mdlMenuRole->delByRole($id);
        return $flag;
    }

    /**
     * 修改状态
     */
    public function setStatus($id, $status)
    {
        if ($id < 1) {
            return false;
        }
        $now = date('Y-m-d H:i:s');
        return $this->where('id', $id)->update(['status' => $status, 'update_time' => $now]);
    }

    /**
     * 取所有角色列表
     * @param int $status 限制状态值(-1:全部 0:禁用 1:正常)
     * @param int $offset 偏移值
     * @param int $limit 返回数量
     * @return array 记录数组
     * @throws Exception
     */
    public function getList($status=-1, $offset=0, $limit=0)
    {
        $this->fields('*');
        if ($status > -1) {
            $this->where('status', $status);
        }
        if ($limit > 0) {
            $this->limit($offset, $limit);
        }
        $rows = $this->select();
        if (!$rows) {
            return [];
        }
        return array_combine(array_column($rows, 'id'), $rows);
    }

    /**
     * 查询一指ID对应的名称
     * @param array $roles 角色ID列表
     * @return array 名称列表
     * @throws Exception
     */
    public function getNames($roles)
    {
        if (!is_array($roles) || empty($roles)) {
            return '';
        }
        $arr = $this->fields('id,name')->where('id', 'IN', $roles)->select();
        return array_combine(array_column($arr, 'id'), array_column($arr, 'name'));
    }

    /**
     * 修改角色名称
     * @param int $id 角色ID
     * @param string $name 名称
     * @return boolean 是否成功
     * @throws Exception
     */
    public function changeName($id, $name)
    {
        if ($id < 1 || empty($name)) {
            return false;
        }
        return $this->where('id', $id)->update(['name' => $name]);
    }
}