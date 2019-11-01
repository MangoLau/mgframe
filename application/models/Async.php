<?php
use Frame\Core\MG;

/**
 * 异步执行队列
 *
 */
class AsyncModel
{
    /**
     * 命令列表
     * @var integer
     */
    const CMD_TEST = 1000; //测试目录的命令，仅把参数打印到日志中

    /**
     * Redis缓冲队列KEY
     * @var string
     */
    private $_key = 'ASYNC_QUEUE';

    /**
     * 消息最大长度
     * @var int
     */
    const MAX_MSG_SIZE = 4096;

    /**
     * 最大附加参数数量
     */
    const MAX_PARAMETER_NUMBER = 21;

    /**
     * 消息字段分隔符
     * @var string
     */
    private $_separator = "\x08";

    /**
     * 添加异步调用任务
     * @param string $api 接口名
     *      形式2：x->y，例如：apilog->push 即 Model_Apilog::push() 方法
     *      形式3：n(n是数字)，接口名等于 async.exec，该数字值将作为第一个参数传递给async.exec)
     * @param string $p1 附件参数1
     * @param string $p2 附件参数2
     * @param string $p3 附件参数3
     * @param string $p4 附件参数4
     * @param string $p5 附件参数5
     * @param string $p6 附件参数6
     * @param string $p7 附件参数7
     * @param string $p8 附件参数8
     * @param string $p9 附件参数9
     * @param string $p10 附件参数10
     * @param string $p11 附件参数11
     * @param string $p12 附件参数12
     * @param string $p13 附件参数13
     * @param string $p14 附件参数14
     * @param string $p15 附件参数15
     * @param string $p16 附件参数16
     * @param string $p17 附件参数17
     * @param string $p18 附件参数18
     * @param string $p19 附件参数19
     * @param string $p20 附件参数20
     * @return bool 是否成功
     */
    public function push()
    {
        //检查参数个数
        $num = func_num_args();
        if($num == 0 || $num > self::MAX_PARAMETER_NUMBER) {
            return false;
        }
        //校正参数类型
        $args = func_get_args();
        for($i=0; $i<$num; $i++) {
            $args[$i] = strval($args[$i]);
        }
        //检查消息长度
        $msg = implode($this->_separator, $args);
        if(strlen($msg) > self::MAX_MSG_SIZE) {
            return false;
        }
        //发送消息
        return MG::redis('async')->rPush($this->_key, $msg);
    }

    /**
     * 从队列中阻塞的获取一条记录(无数据或KEY错误的时候都会阻塞，连接断开时会抛出异常)
     * @param number $timeout 要获取的记录数
     */
    public function blockingShift($timeout=10)
    {
        $item = MG::redis('async')->blPop($this->_key, $timeout);
        if(!$item) {
            return array();
        }
        $msg = $item[1];
        //检查数据
        $array = explode($this->_separator, $msg, self::MAX_PARAMETER_NUMBER);
        if(!is_array($array) || empty($array)) {
            return array();
        }
        //如果接口名是数字，将接口名设置为async.exec并且将数字当作它的命令字
        if(is_numeric($array[0])) {
            array_unshift($array, 'async.exec');
        }
        //返回数据
        $ret[] = $array;
        return $ret;
    }
}