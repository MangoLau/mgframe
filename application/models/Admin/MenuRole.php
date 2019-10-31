<?php
/**
 * @author MangoLau
 */

class Admin_MenuRoleModel extends \Frame\Core\Dao
{
    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'menurole';
        parent::__construct($table, $dbalias);
    }

    /**
     * 添加记录
     * @param int $menuid 后台菜单模块ID
     * @param int $roleid 角色ID
     * @return boolean 是否成功
     * @throws Exception
     */
    public function add($menuid, $roleid)
    {
        if ($menuid < 1 || $roleid < 1) {
            return false;
        }
        return $this->insert(array('menuid' => $menuid, 'roleid' => $roleid));
    }

    /**
     * 批量添加记录
     * @param int $menuid 后台菜单模块ID
     * @param array $roles 角色ID列表
     * @return boolean 是否成功
     * @throws Exception
     */
    public function batAdd($menuid, $roles)
    {
        if ($menuid < 1 || !is_array($roles) || empty($roles)) {
            return false;
        }
        foreach ($roles as $roleid) {
            $this->insert(array('menuid' => $menuid, 'roleid' => $roleid));
        }
        return true;
    }

    /**
     * 使用记录ID删除
     * @param int $id 记录ID
     * @return boolean 是否成功
     * @throws Exception
     */
    public function del($id)
    {
        if ($id < 1) {
            return false;
        }
        return $this->where('id', $id)->delete();
    }

    /**
     * 使用后台菜单模块ID和角色ID删除记录
     * @param int $menuid 后台菜单模块ID
     * @param int $roleid 角色ID
     * @return bool 是否成功
     * @throws Exception
     */
    public function delByMenuAndRole($menuid, $roleid)
    {
        if ($menuid < 1 || $roleid < 1) {
            return false;
        }
        return $this->where(array('menuid' => $menuid, 'roleid' => $roleid))->delete();
    }

    /**
     * 删除指定后台菜单模块下的所有记录
     * @param int $menuid 后台菜单模块ID
     * @return bool 是否成功
     * @throws Exception
     */
    public function delByMenu($menuid)
    {
        if ($menuid < 1) {
            return false;
        }
        return $this->where('menuid', $menuid)->delete();
    }

    /**
     * 删除指定角色下的所有记录
     * @param int $roleid 角色ID
     * @return boolean 是否成功
     * @throws
     */
    public function delByRole($roleid)
    {
        if ($roleid < 1) {
            return false;
        }
        return $this->where('roleid', $roleid)->delete();
    }

    /**
     * 查询指定后台菜单模块的所有角色列表
     * @param int $menuid 后台菜单模块ID
     * @return array|bool 成功返回可访问的角色ID列表，失败返回false
     * @throws
     */
    public function getRolesByMenu($menuid)
    {
        if ($menuid < 1) {
            return false;
        }
        $rows = $this->fields('roleid')->where('menuid', $menuid)->select();
        return is_array($rows) && !empty($rows) ? array_column($rows, 'roleid') : array();
    }

    /**
     * 查询指定角色可访问的后台菜单模块ID列表
     * @param int $roleid 角色ID
     * @return array|bool 成功返回可访问的后台菜单模块ID列表，失败返回false
     */
    public function getMenusByRole($roleid)
    {
        if ($roleid < 1) {
            return false;
        }
        $rows = $this->fileds('menuid')->where('roleid', $roleid)->select();
        return is_array($rows) && !empty($rows) ? array_column($rows, 'menuid') : array();
    }

    /**
     * 查询指定角色列表可访问的模块ID列表
     * @param array $roles 角色ID列表
     * @return array 可访问的模块ID列表
     * @throws
     */
    public function getMenusByRoles($roles)
    {
        if (!is_array($roles) || empty($roles)) {
            return false;
        }
        $rows = $this->fields('menuid')->where('roleid', 'IN', $roles)->select();
        return is_array($rows) && !empty($rows) ? array_column($rows, 'menuid') : array();
    }

    /**
     * 获取一批后台模块ID对应的角色列表
     * @param array $moduleIds
     * @return array
     * @throws
     */
    public function getRolesByMenus($moduleIds)
    {
        if (!is_array($moduleIds) || empty($moduleIds)) {
            return [];
        }
        $rows = $this->fields(['menuid', 'roleid'])->where('menuid', 'IN', $moduleIds)->select();
        if (!$rows) {
            return [];
        }
        $ret = array();
        foreach ($rows as $row) {
            $ret[$row['menuid']][] = intval($row['roleid']);
        }
        return $ret;
    }
}