<?php
/**
 * @author MangoLau
 */

class Admin_AdminModel extends \Frame\Core\Dao
{
    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'admin';
        parent::__construct($table, $dbalias);
    }

    /**
     * 根据用户名取一条记录
     * @param $account
     * @return mixed
     * @throws Exception
     */
    public function getByAccount($account)
    {
        return $this->where('account', $account)->fields('*')->getOne();
    }

    /**
     * 更新最后登录信息(IP和时间)
     * @param $uid
     * @return Admin_AdminModel|mixed
     * @throws Exception
     */
    public function updateLastLogin($uid)
    {
        return $this->where('id', $uid)->update([
            'last_ip' => Func::getClientIp(),
            'last_login_time' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 取所有管理员列表
     * @param int $status 限制状态值(-1:全部 0:禁用 1:正常)
     * @param int $offset 偏移值
     * @param int $limit 返回数量
     * @return array 记录数组
     * @throws Exception
     */
    public function getList($status=-1, $offset=0, $limit=0)
    {
        $this->fields(['id', 'account', 'name', 'status', 'last_login_time', 'create_time']);
        if ($status > -1) {
            $this->where('status', $status);
        }
        if ($limit > 0) {
            $this->limit($offset, $limit);
        }
        return $this->select();
    }

    /**
     * 更新指定的字段信息
     * @param int $uid 管理员ID
     * @param string $field 字段名
     * @param string $value 新字段值
     * @return boolean 是否成功
     * @throws \Exception
     */
    public function updateField($uid, $field, $value)
    {
        $allowed = array('name', 'passwd', 'status');
        if ($uid < 1 || !in_array($field, $allowed)) {
            return false;
        }
        if ($field == 'name') {
            $value = htmlspecialchars($value);
        } elseif ($field == 'passwd') {
            $value = password_hash($value, PASSWORD_BCRYPT);
        }
        $data = array(
            $field => $value,
            'update_time' => date('Y-m-d H:i:s'),
        );
        return $this->where('id', $uid)->update($data);
    }

    /**
     * 删除指定的管理员账号
     * @param int $uid 管理员ID
     * @return boolean 是否成功
     * @throws \Exception
     */
    public function del($uid)
    {
        if ($uid < 1) {
            return false;
        }
        $flag = $this->where('id', $uid)->delete();
        if ($flag) {
            //同时删除分配的角色
            $adminrole = new Admin_AdminRoleModel();
            $adminrole->delByUid($uid);
        }
        return $flag;
    }

    /**
     * 添加记录
     * @param string $account 账号名
     * @param string $name 名称
     * @param string $pwd 密码
     * @return boolean|int 成功返回新记录ID，失败返回false
     * @throws \Exception
     */
    public function add($account, $name, $pwd)
    {
        $now = date('Y-m-d H:i:s');
        $flag = $this->insert(array(
            'account' => $account,
            'passwd' => password_hash($pwd, PASSWORD_BCRYPT),
            'name' => htmlspecialchars($name),
            'status' => 1,
            'create_time' => $now,
            'update_time' => $now,
        ));
        if (!$flag) {
            return false;
        }
        return $this->getLastInsertId();
    }

    /**
     * 根据ID获取id->name映射集
     * @param array $ids
     * @return array|bool
     * @throws Exception
     */
    public function getAdminByIds(array $ids)
    {
        if (empty($ids)) {
            return false;
        }
        $res = $this
            ->fields('id,name')
            ->where('id', 'IN', $ids)
            ->select();
        return $res ? array_column($res, 'name', 'id') : false;
    }
}

