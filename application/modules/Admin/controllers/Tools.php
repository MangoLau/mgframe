<?php
use Frame\Core\MG;

/**
 * 管理后台的工具页面
 *
 */
class ToolsController extends Admin_ProtectedController
{
    public function indexAction()
    {
        $vars = [
            'layout_title' => '开发工具',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '开发工具', 'active' => 1]
            ],
            'layout_css' => [
                'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
            ],
            'layout_script' => [
                'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
                'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
            ],
            'layout_js_vars' => [
            ],
        ];
        return $this->layout('index', $vars);
    }

    /**
     * 远程代码执行
     * @return boolean
     */
    public function execAction()
    {
        if ($_POST['code']) {
            try {
                ob_start();
                eval($_POST['code']);
                $output = ob_get_contents();
                ob_end_clean();
            } catch (Exception $e) {
                $output = $e->__toString();
            } catch (Error $e) {
                $output = $e->__toString();
            }
            return $this->json(0, '', ['output' => $output]);
        }
        $vars = [
            'layout_title' => '开发工具',
            'layout_desc' => '远程代码执行',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '开发工具', 'url' => '/admin/tools/index'],
                ['name' => '远程执行', 'active' => 1]
            ],
            'layout_css' => [
                'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
            ],
            'layout_script' => [
                'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
                'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                'admin/tools/page_tools_exec.js',
            ],
            'layout_js_vars' => [
            ],
        ];
        return $this->layout('exec', $vars);
    }

    /**
     * redis运行情况
     */
    public function cacheAction()
    {
        if ($_POST['load']) {
            $redisServers = Func::config('redis');
            $redisStats	  = array();
            foreach($redisServers as $name => $server) {
                $redis = MG::redis($name);
                $value = $redis->info();
                $value['server'] = "{$server['host']}:{$server['port']}";
                if($value['process_id']) {
                    $item = $redis->config('GET', 'maxmemory');
                    $value['maxmemory'] = $item['maxmemory'];
                    $item = $redis->config('GET', 'databases');
                    $value['databases'] = $item['databases'] ? intval($item['databases']) : 16;
                } else {
                    $value['process_id'] = 0;
                }
                $redisStats[$name] = $value;
            }
            return $this->json(0, '', $redisStats);
        }
        $vars = [
            'layout_title' => '开发工具',
            'layout_desc' => '缓存状态监控',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '开发工具', 'url' => '/tools/index'],
                ['name' => '缓存状态监控', 'active' => 1]
            ],
            'layout_css' => [
                'adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
            ],
            'layout_script' => [
                'adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
                'adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                'admin/tools/page_tools_cache.js',
            ],
        ];
        return $this->layout('cache', $vars);
    }

    /**
     * 时间戳转换
     */
    public function timestampAction()
    {
        $vars = [
            'layout_title' => '开发工具',
            'layout_desc' => '时间戳转换',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '开发工具', 'url' => '/admin/tools/index'],
                ['name' => '时间戳转换', 'active' => 1]
            ],
            'layout_css' => [
                'admin/tools/page_timestamp.css',
            ],
            'layout_script' => [
                'admin/tools/page_timestamp.js',
            ],
            'layout_js_vars' => [
            ],
        ];
        return $this->layout('timestamp', $vars);
    }

    /**
     * JSON格式化工具
     */
    public function jsonAction()
    {
        $vars = [
            'layout_title' => '开发工具',
            'layout_desc' => 'JSON',
            'layout_links' => [
                ['name' => '首页', 'url' => '/admin/index/index', 'icon'  => 'fa-dashboard'],
                ['name' => '开发工具', 'url' => '/admin/tools/index'],
                ['name' => 'JSON', 'active' => 1]
            ],
            'layout_css' => [
                'admin/tools/page_json.css'
            ],
            'layout_script' => [
                'admin/tools/jsoneditor.js',
            ],
            'layout_js_vars' => [
            ],
        ];
        return $this->layout('json', $vars);
    }
}