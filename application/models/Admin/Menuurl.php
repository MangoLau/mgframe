<?php
/**
 * @author MangoLau
 */

class Admin_MenuurlModel extends \Frame\Core\Dao
{
    public function __construct(string $table = '', string $dbalias = '')
    {
        $table = 'menuurl';
        parent::__construct($table, $dbalias);
    }

    /**
     * 添加记录
     * @param int $menuid 管理后台菜单模块ID
     * @param string $url 接口地址
     * @return boolean 是否成功
     * @throws Exception
     */
    public function add($menuid, $url)
    {
        $url = preg_replace('/[^\w*\/]/', '', $url);
        if ($menuid < 1 || empty($url)) {
            return false;
        }
        return $this->insert(['menuid' => $menuid, 'url' => strtolower($url)]);
    }

    /**
     * 批量添加记录
     * @param int $menuid 管理后台菜单模块ID
     * @param array $urls 接口地址列表
     * @return boolean 是否成功
     * @throws Exception
     */
    public function batAdd($menuid, $urls)
    {
        if ($menuid < 1 || !is_array($urls) || empty($urls)) {
            return false;
        }
        foreach ($urls as &$url) {
            $url = preg_replace('/[^\w*\/]/', '', $url);
            if (empty($url)) {
                continue;
            }
            $this->insert(['menuid' => $menuid, 'url' => strtolower($url)]);
        }
        return true;
    }

    /**
     * 使用记录ID删除一条记录
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
     * 使用管理后台菜单模块ID和URL删除记录
     * @param int $menuid 模块ID
     * @param string $url 接口地址
     * @return boolean 是否成功
     * @throws Exception
     */
    public function delByMenuAndUrl($menuid, $url)
    {
        if ($menuid < 1 || empty($url)) {
            return false;
        }
        return $this->where(['menuid' => $menuid, 'url' => $url])->delete();
    }

    /**
     * 使用模块ID删除对应的记录ID
     * @param int $menuid 模块ID
     * @return boolean 是否成功
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
     * 获取一批后台模块ID对应的URL列表
     * @param array $moduleIds
     * @return array
     * @throws Exception
     */
    public function getListByMenus($moduleIds)
    {
        if (!is_array($moduleIds) || empty($moduleIds)) {
            return [];
        }
        $rows = $this->fields(['menuid', 'url'])->where('menuid', 'IN', $moduleIds)->orderby('id')->select();
        if (!$rows) {
            return [];
        }
        $ret = array();
        foreach ($rows as $row) {
            $ret[$row['menuid']][] = $row['url'];
        }
        return $ret;
    }

    /**
     * 取指定URL对应的模块ID
     * @param array $urls URL列表
     * @return array|array 模块ID列表
     * @throws Exception
     */
    public function getMenusByUrls($urls)
    {
        if (!is_array($urls) || empty($urls)) {
            return [];
        }
        $rows = $this->fields('DISTINCT menuid')->where('url', 'IN', $urls)->select();
        return is_array($rows) ? array_column($rows, 'menuid') : [];
    }
}