<?php
use Frame\Core\Redis;
use Frame\Core\Db;
use Frame\Core\MG;

/**
 * 异步处理的类
 */
class AsyncController extends Common_PublicController
{
    /**
     * 处理异步任务
     */
    public function daemonAction()
    {
        $req    = $this->getRequest();
        $p		= intval($req->getParam('p')); //进程编号，用于多进程并行处理
        $run	= ROOT_PATH . "/logs/async.daemon.{$p}.run";
        $die	= ROOT_PATH . "/logs/async.daemon.{$p}.die";
        //判断是否已运行
        clearstatcache(); //清除PHP文件信息缓存
        if(file_exists($run)) {
            $pid = file_get_contents($run);
            $num = shell_exec("ps aux | grep '{$pid}' | grep 'request_uri=/async/daemon/p/{$p}' | grep -v 'grep' | wc -l");
            if(intval($num) > 0 && time() - fileatime($run) < 300) {
                return;
            } else {
                shell_exec("ps aux | grep '{$pid}' | grep 'request_uri=/async/daemon/p/{$p}' | grep -v 'grep' | awk '{print $2}' | grep {$pid} | xargs --no-run-if-empty kill");
            }
        }
        //设置运行状态
        if(!file_put_contents($run, getmypid())) {
            return;
        }
        //循环处理
        $queue = new AsyncModel();
        while(1) {
            //检查是否需要重启
            if(file_exists($die) && unlink($die) && unlink($run)) {
                return;
            }
            //更新执行时间
            touch($run);
            //获取一批任务
            $tasks = $queue->blockingShift(5);
            if(empty($tasks)) {
                //数据连接保活
                Db::keepAlive();
                Redis::keepAlive();
                continue;
            }
            //批量处理
            foreach ($tasks as $task) {
                $act = array_shift($task);
                if($act == 'async.exec') {
                    $func = array($this, 'exec');
                } else {
                    $pos = strrpos($act, '->');
                    if ($pos === false) {
                        /*
                        $pos = strrpos($act, '.');
                        if($pos === false) {
                            $className	= ucfirst($act) . 'Controller';
                            $methodName	= 'indexAction';
                        } else {
                            $className	= ucfirst(substr($act, 0, $pos)) . 'Controller';
                            $methodName	= substr($act, $pos + 1) . 'Action';
                        }
                        $obj = empty($className) ? null : ($className == 'AsyncController' ? $this : (new $className()));
                        */
                    } else {
                        $className	= ucfirst(substr($act, 0, $pos)) . 'Model';
                        $methodName	= substr($act, $pos + 2);
                        $obj = empty($className) ? null : (new $className());
                    }
                    if(!$obj || empty($methodName)) {
                        MG::log()->error("Unknown async action '{$act}'!", E_USER_WARNING);
                        continue;
                    }
                    $func	= array($obj, $methodName);
                }
                $flag	= call_user_func_array($func, $task);
                if($flag === false) {
                    MG::log()->error("Call async action '{$act}' failed!", E_USER_WARNING);
                }
            }
        }
    }

    /**
     * 处理任务
     */
    public function exec()
    {
        $args = func_get_args();
        $cmd = array_shift($args);
        $cmd = intval($cmd);
        switch($cmd) {
            case AsyncModel::CMD_TEST: //测试
                MG::log()->debug("Test async.exec, args=" . implode(',', $args));
                $this->demo(['data' => 'test']);
                break;
            default: //未知
                MG::log()->error("Unknow async.exec cmd, cmd={$cmd}");
                break;
        }
    }

    /**
     * demo
     * @param $args
     * @return bool
     * @throws Exception
     */
    public function demo($args)
    {
        if (empty($args)) {
            return false;
        }
        MG::log()->debug('demo');
        return true;
    }
}