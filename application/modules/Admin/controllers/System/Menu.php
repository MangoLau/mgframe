<?php
/**
 * 管理后台菜单管理相关的接口
 * @author weicky
 *
 */
class System_MenuController extends Admin_ProtectedController
{
	/**
	 * 管理首页
	 */
	public function indexAction()
    {
        $mdlRole = new Admin_RoleModel();
        $roleList = $mdlRole->getList();
        $mdlMenus = new Admin_MenusModel();
        $topMenus = $mdlMenus->getTopMenus(-1);
        $this->getView()->assign('roleList', $roleList);
        $this->getView()->assign('topMenus', $topMenus);
        $vars = [
            'layout_title' => '后台菜单管理',
            'layout_desc' => '添加、删除、修改管理后台的菜单列表',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '后台菜单管理', 'active' => 1]
            ],
            'layout_css' => [
                'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
            ],
            'layout_script' => [
                'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
                'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                'admin/icons.js',
                'admin/system/menu.js',
            ],
            'layout_js_vars' => [
                'roles' => $roleList,
                'topMenus' => $topMenus,
            ],
        ];
        return $this->layout('index', $vars);
	}

	/**
	 * 列表接口
	 */
	public function listAction()
	{
	    /*
	     * 请求字段介绍
	     * $draw - 用于防跨站攻击，直接透传回去，需要转成数字类型
	     * $start - 当前分页记录的偏移值，数字
	     * $length - 分页记录数量，数字
	     * $search - 搜索数据，数组
	     *     [
	     *         'value' => '1', //搜索关键字
	     *         'regex' => false, //是否使用正则匹配
	     *     ]
	     * $order - 排序数据，二组数组
	     *     [
	     *         [
	     *             'column' => 1, //排序列编号
	     *             'dir' => 'asc', //排序方式
	     *         ]
	     *     ]
	     * $columns - 列信息，三维数组
	     *     [
	     *         [
	     *             'data' => 'menuid', //列在数据源中字段名称
	     *             'name' => '', //列的展示名称
	     *             'searchable' => true, //是否可搜索
	     *             'orderable' => true, //是否可排序
	     *             'search' => [ //搜索数据
	     *                 'value' => '1', //搜索关键字
	     *                 'regex' => false, //是否使用正则匹配
	     *             ]
	     *         ]
	     *     ]
	     */
	    
	    /*
	     * 返回字段要求
	     * draw - 请求的$draw值，需要转为数字
	     * recordsTotal - 所有记录数，应当是未经过分页和过滤的总数
	     * recordsFiltered - 过滤后的总数，不仅是当前分页
	     * data - 数据列表
	     * error - 错误信息
	     */
	    
	    /*
	     * data字段中每条记录可扩展的字段
	     *     DT_RowId - TR标签中的ID属性值，字符串
	     *     DT_RowClass - TR标签的额外class样式名，字符串
	     *     DT_RowData - TR标签的data值，可以通过JQ的data()方法提取，关联数组
	     *     DT_RowAttr - TR标签的额外属性值，关联数组
	     */
	    $req = $this->getRequest();
	    $draw = intval($req->getPost('draw'));
	    $start = intval($req->getPost('start'));
	    $length = intval($req->getPost('length'));
	    $search = $req->getPost('search');
	    $order = $req->getPost('order');
	    $columns = $req->getPost('columns');
	    $keyword = !empty($search) && !empty($search['value']) ? $search['value'] : '';
	    $orderbycol = $order[0]['column'];
	    $orderdir = $order[0]['dir'];
	    $orderby = isset($orderbycol) && isset($columns[$orderbycol]) ? $columns[$orderbycol]['data'] : 'menuid';
	    $mdlMenus = new Admin_MenusModel();
	    $total = $mdlMenus->countAll();
	    $totalFiltered = $keyword == '' ? $total : $mdlMenus->countAll($keyword);
	    $list = $mdlMenus->getList($keyword, $start, $length, $orderby, $orderdir);
	    if (!empty($list)) {
	        $ids = array_column($list, 'menuid');
	        //填充URL列表
	        $mdlMenuUrl = new Admin_MenuurlModel();
	        $urls = $mdlMenuUrl->getListByMenus($ids);
	        foreach ($list as &$item) {
	            $item['urls'] = isset($urls[$item['menuid']]) ? $urls[$item['menuid']] : [];
	        }
	        //填充角色列表
	        $mdlMenuRole = new Admin_MenuroleModel();
	        $roles = $mdlMenuRole->getRolesByMenus($ids);
	        foreach ($list as &$item) {
	            $item['roles'] = isset($roles[$item['menuid']]) ? $roles[$item['menuid']] : [];
	        }
	    }
	    $ret = array(
	        'draw' => $draw,
	        'recordsTotal' => $total,
	        'recordsFiltered' => $totalFiltered,
	        'data' => $list,
	        'error' => '',
	    );
	    echo json_encode($ret);
	    return false;
	}

	/**
	 * 添加菜单模块
	 */
	public function addAction()
	{
	    $req = $this->getRequest();
	    $menu = $req->getPost('menu');
	    $parentId = $req->getPost('parent_id');
	    $indexUrl = $req->getPost('index_url');
	    $urls = $req->getPost('urls');
	    $roles = $req->getPost('roles');
	    $sortNo = intval($req->getPost('sort_no'));
	    $icon = $req->getPost('icon');
	    if (empty($menu)) {
	        return $this->json(500, '名称不能为空！');
	    } elseif (!is_array($roles) || empty($roles)) {
	        return $this->json(500, '授权角色不能为空！');
	    }
	    $model = new Admin_MenusModel();
	    $menuid = $model->add($menu, $indexUrl, $parentId, $sortNo, $icon);
	    if (!$menuid) {
	        return $this->json(500, '操作失败，请重试！');
	    }
	    //合并该菜单模块对应的URL列表
	    if (!empty($indexUrl)) {
	        if (empty($urls)) {
	            $urls = [$indexUrl];
	        } elseif (!in_array($indexUrl, $urls)) {
	            array_unshift($urls, $indexUrl);
	        }
	    }
	    //将该模块与其URL创建映射关系
	    if (!empty($urls)) {
	        $mdlMenuUrl = new Admin_MenuurlModel();
	        $mdlMenuUrl->batAdd($menuid, $urls);
	    }
	    //将该模块与角色建立映射关系
	    if (!empty($roles)) {
	        $mdlMenuRole = new Admin_MenuroleModel();
	        $mdlMenuRole->batAdd($menuid, $roles);
	    }
	    return $this->json(0, '');
	}

	/**
	 * 删除接口
	 */
	public function delAction()
	{
	    $req = $this->getRequest();
	    $menuid = intval($req->getPost('menuid'));
	    if ($menuid < 1) {
	        return $this->json(500, '请选择要删除的记录！');
	    }
	    $model = new Admin_MenusModel();
	    $flag = $model->del($menuid);
	    if ($flag) {
	        return $this->json(0, '');
	    } else {
	        return $this->json(500, '操作失败，请重试！');
	    }
	}

	/**
	 * 更新接口
	 */
	public function updateAction()
	{
	    $req = $this->getRequest();
	    $menuid = intval($req->getPost('menuid'));
	    $menu = $req->getPost('menu');
	    $parentId = $req->getPost('parent_id');
	    $indexUrl = $req->getPost('index_url');
	    $urls = $req->getPost('urls');
	    $roles = $req->getPost('roles');
	    $sortNo = intval($req->getPost('sort_no'));
	    $icon = $req->getPost('icon');
	    if (empty($menu)) {
	        return $this->json(500, '名称不能为空！');
	    } elseif (!is_array($roles) || empty($roles)) {
	        return $this->json(500, '授权角色不能为空！');
	    }
	    $model = new Admin_MenusModel();
	    $data = [
	        'menu' => $menu,
	        'index_url' => $indexUrl,
	        'parent_id' => $parentId,
	        'sort_no' => $sortNo,
	        'icon' => $icon,
	    ];
	    $flag = $model->updateRecord($menuid, $data);
	    if (!$flag) {
	        return $this->json(500, '操作失败，请重试！');
	    }
	    $mdlMenuUrl = new Admin_MenuurlModel();
	    $mdlMenuRole = new Admin_MenuroleModel();
	    //删除旧的URL数据和角色数据
	    $mdlMenuUrl->delByMenu($menuid);
	    $mdlMenuRole->delByMenu($menuid);
	    //合并该菜单模块对应的URL列表
	    if (!empty($indexUrl)) {
	        if (empty($urls)) {
	            $urls = [$indexUrl];
	        } elseif (!in_array($indexUrl, $urls)) {
	            array_unshift($urls, $indexUrl);
	        }
	    }
	    //将该模块与其URL创建映射关系
	    if (!empty($urls)) {
	        $mdlMenuUrl->batAdd($menuid, $urls);
	    }
	    //将该模块与角色建立映射关系
	    if (!empty($roles)) {
	        $mdlMenuRole->batAdd($menuid, $roles);
	    }
	    return $this->json(0, '');
	}

	/**
	 * 禁用接口
	 */
	public function disableAction()
	{
	    $req = $this->getRequest();
	    $menuid = intval($req->getPost('menuid'));
	    if ($menuid < 1) {
	        return $this->json(500, '请选择要禁用的记录！');
	    }
	    $model = new Admin_MenusModel();
	    $flag = $model->updateField($menuid, 'status', 0);
	    if ($flag) {
	        return $this->json(0, '');
	    } else {
	        return $this->json(500, '操作失败，请重试！');
	    }
	}

	/**
	 * 启用接口
	 */
	public function enableAction()
	{
	    $req = $this->getRequest();
	    $menuid = intval($req->getPost('menuid'));
	    if ($menuid < 1) {
	        return $this->json(500, '请选择要启用的记录！');
	    }
	    $model = new Admin_MenusModel();
	    $flag = $model->updateField($menuid, 'status', 1);
	    if ($flag) {
	        return $this->json(0, '');
	    } else {
	        return $this->json(500, '操作失败，请重试！');
	    }
	}
}