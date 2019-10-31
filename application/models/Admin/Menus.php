<?php
/**
 * 后台菜单模块模型类
 */
use Frame\Core\Dao;
use Frame\Core\Db;

class Admin_MenusModel extends Dao
{
    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'menus';
        parent::__construct($table, $dbalias);
    }

    /**
     * 添加一条记录
     * @param string $menu 模块菜单名
     * @param string $indexUrl 首页地址
     * @param int $parentid 父级I模块菜单D
     * @param int $sortNo 排序值(越大越靠前)
     * @param string $icon 图标
     * @param int $status 状态(1:正常 0:禁用)
     * @return bool|int 成功返回新模块的ID，失败返回false
     * @throws \Exception
     */
    public function add($menu, $indexUrl, $parentid, $sortNo=0, $icon='', $status=1)
    {
        $data = array(
            'menu' => $menu,
            'index_url' => preg_replace('/[^\w*\/]/', '', $indexUrl),
            'parent_id' => $parentid,
            'sort_no' => $sortNo,
            'status' => $status,
            'icon' => $icon,
        );
        $flag = $this->insert($data);
        if (!$flag) {
            return false;
        }
        return $this->getLastInsertId();
    }

    /**
     * 删除一条记录
     * @param int $menuid 模块ID
     * @return bool 是否成功
     * @throws \Exception
     */
    public function del($menuid)
    {
        if ($menuid < 1) {
            return false;
        }
        $flag = $this->where('menuid', $menuid)->delete();
        if ($flag) {
            //删除对应的角色配置数据和URL列表
            $mdlMenuRole = new Admin_MenuRoleModel();
            $mdlMenuRole->delByMenu($menuid);
            $mdlMenuUrl = new Admin_MenuurlModel();
            $mdlMenuUrl->delByMenu($menuid);
        }
        return $flag;
    }

    /**
     * 更新记录
     * @param int $menuid 模块ID
     * @param array $data 要更新的字段与值数组
     * @return bool 是否成功
     * @throws Exception
     */
    public function updateRecord($menuid, $data)
    {
        $allowed = array('menu', 'index_url', 'parent_id', 'sort_no', 'status', 'icon');
        foreach ($data as $k => &$v) {
            if (!in_array($k, $allowed)) {
                unset($data[$k]);
            }
            if ($k == 'menu') {
                $v = htmlspecialchars($v);
            } elseif ($k == 'index_url') {
                $v = preg_replace('/[^\w*\/]/', '', $v);
            }
        }
        return $this->where('menuid', $menuid)->update($data);
    }

    /**
     * 更新记录字段
     */
    public function updateField($menuid, $field, $value)
    {
        $allowed = array('menu', 'index_url', 'parent_id', 'sort_no', 'status', 'icon');
        if ($menuid < 1 || !in_array($field, $allowed)) {
            return false;
        }
        if ($field == 'menu') {
            $value = htmlspecialchars($value);
        } elseif ($field == 'index_url') {
            $value = preg_replace('/[^\w*\/]/', '', $value);
        }
        return $this->where('menuid', $menuid)->update(array($field => $value));
    }

    /**
     * 返回所有的记录数量
     */
    public function countAll($search='')
    {
        if (!empty($search)) {
            if (is_numeric($search)) {
                $this->where('menuid', $search);
            } else {
                $this->orwhere('menu', 'LIKE', '%' . $search . '%');
                $this->orwhere('index_url', 'LIKE', '%' . $search . '%');
            }
        }
        return $this->count(1);
    }

    /**
     * 按页返回记录列表
     * @param string $search 搜索
     * @param int $offset 偏移值
     * @param int $limit 返回数量
     * @param string $orderby 排序字段
     * @parma string $orderdir 排序方式
     * @return array|bool 成功返回记录列表，失败返回false
     * @throws Exception
     */
    public function getList($search='', $offset=0, $limit=20, $orderby='menuid', $orderdir='ASC')
    {
        $this->fields('*');
        if (!empty($search)) {
            if (is_numeric($search)) {
                $this->where('menuid', $search);
            } else {
                $this->orwhere('menu', 'LIKE', '%' . $search . '%');
                $this->orwhere('index_url', 'LIKE', '%' . $search . '%');
            }
        }
        $orderdir = strtoupper($orderdir);
        if (in_array($orderby, array('menuid', 'menu', 'index_url', 'parent_id', 'sort_no', 'status')) && in_array($orderdir, array('ASC', 'DESC'))) {
            $order = "{$orderby} {$orderdir}";
        } else {
            $order = "menuid";
        }
        if ($limit > 0) {
            $this->limit($offset, $limit);
        }
        return $this->orderby($order)->select();
    }

    /**
     * 获取指定的一批ID的模块记录列表
     * @param array $menuids 模块ID列表
     * @param int $status 模块状态限制(-1:所有 1:正常 0:禁用)
     * @return array|bool 成功返回记录数组，失败返回false
     * @throws Exception
     */
    public function getListByIds($menuids, $status=1)
    {
        if (!is_array($menuids) || empty($menuids)) {
            return [];
        }
        $this->where('menuid', 'IN', $menuids);
        if ($status > -1) {
            $this->where('status', $status);
        }
        return $this->orderby('sort_no DESC')->select();
    }
 
    /**
     * 取一级菜单模块
     */
    public function getTopMenus($status=1)
    {
        if ($status > -1) {
            $this->where('status', $status);
        }
        $rows = $this->fields(['menuid', 'menu', 'status', 'icon'])->select();
        return is_array($rows) ? array_combine(array_column($rows, 'menuid'), $rows) : [];
    }
}