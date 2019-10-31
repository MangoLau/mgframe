<?php
/**
 * @author MangoLau
 */

class Admin_AdminRoleModel extends \Frame\Core\Dao
{

    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'adminrole';
        parent::__construct($table, $dbalias);
    }

    /**
     * 添加记录
     * @param $uid
     * @param $role
     * @return bool|mixed
     * @throws Exception
     */
    public function add($uid, $role)
    {
        if ($uid < 1 || $role < 1) {
            return false;
        }
        return $this->insert(['uid' => $uid, 'roleid' => $role]);
    }

    /**
     * 删除记录
     * @param int $id
     * @return boolean|array|boolean
     * @throws \Exception
     */
    public function del($id)
    {
        if ($id < 1) {
            return false;
        }
        return $this->where('id', $id)->delete();
    }

    /**
     * 使用uid和角色id删除
     * @param int $uid
     * @param int $role
     * @return boolean|array|boolean
     * @throws Exception
     */
    public function delByUidAndRole($uid, $role)
    {
        if ($uid < 1 || $role < 1) {
            return false;
        }
        return $this->where(['uid' => $uid, 'roleid' => $role])->delete();
    }

    /**
     * 删除指定角色的所有记录
     * @param int $roleid 角色ID
     * @return bool 是否成功
     * @throws Exception
     */
    public function delByRole($roleid)
    {
        if ($roleid < 1) {
            return false;
        }
        return $this->where('roleid', $roleid)->delete();
    }

    /**
     * 删除指定用户的所有记录
     * @param int $uid 管理员ID
     * @return boolean 是否成功
     * @throws Exception
     */
    public function delByUid($uid)
    {
        if ($uid < 1) {
            return false;
        }
        return $this->where('uid', $uid)->delete();
    }

    /**
     * 取用户的角色列表
     * @param int $uid
     * @return array|mixed|array|boolean
     * @throws
     */
    public function getRolesByUid($uid)
    {
        if ($uid < 1) {
            return [];
        }
        $rows = $this->fields('roleid')->where('uid', $uid)->select();
        if (is_array($rows) && !empty($rows)) {
            $ids = array_column($rows, 'roleid');
            $modelRole = new Admin_RoleModel();
            return $modelRole->getNames($ids);
        } else {
            return [];
        }
    }

    /**
     * 取用户的所有记录
     * @param int $uid
     * @return array|mixed|array|boolean
     * @throws
     */
    public function getListByUid($uid)
    {
        if ($uid < 1) {
            return [];
        }
        return $this->fields('*')->where('uid', $uid)->select();
    }

    /**
     * 使用角色ID获取对应的管理员ID列表
     * @param int $role
     * @return array|array
     * @throws
     */
    public function getUidsByRole($role)
    {
        if ($role < 1) {
            return [];
        }
        $rows = $this->fields('uid')->where('roleid', $role)->select();
        return is_array($rows) ? array_column($rows, 'uid') : [];
    }

    /**
     * 取一批管理员的角色列表
     * @param array $uids 管理员ID列表
     * @return array 每个管理员对应的角色ID列表
     * @throws
     */
    public function getRolesByUids($uids)
    {
        if (!is_array($uids) || empty($uids)) {
            return [];
        }
        $rows = $this->fields(['uid', 'roleid'])->where('uid', 'IN', $uids)->select();
        if (!$rows) {
            return [];
        }
        $ret = [];
        foreach ($rows as $row) {
            $ret[$row['uid']][] = $row['roleid'];
        }
        return  $ret;
    }
}