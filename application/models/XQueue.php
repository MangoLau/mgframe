<?php

/**
 * 用于并行执行任务的队列
 */
class XQueueModel
{
    /**
     * 消息队列的句柄
     * @var resource
     */
    private $handle;

    /**
     * 使用到的sysvmsg队列的key
     */
    const SYSVMSG_KEY = 0x8090;

    /**
     * 消息内容的最大长度
     */
    const SYSVMSG_MAXSIZE = 2048;

    /**
     * 要求并行执行管理进程退出的消息类型
     */
    const DAEMON_QUIT = 9;

    /**
     * 要求并行执行管理进程重启的消息类型
     */
    const DAEMON_RESTART = 10;

    /**
     * 构造
     * @throws Exception
     */
    public function __construct()
    {
        $this->handle = msg_get_queue(self::SYSVMSG_KEY, 0666);
        if (!$this->handle) {
            throw new \Exception("msg_get_queue() failed!");
        }
    }

    /**
     * 往队列中添加消息
     * @param string $task 任务标识
     * @param mixed $args 参数
     * @return bool 是否成功
     */
    public function push($task, $args)
    {
        $msg = json_encode(['task' => $task, 'args' => $args]);
        if (strlen($msg) > self::SYSVMSG_MAXSIZE) {
            return false;
        }
        return msg_send($this->handle, 1, $msg, false, false);
    }

    /**
     * 从队列中取一条消息
     * @return bool|int|string 成功返回消息内容或指定ID，失败返回false
     */
    public function shift()
    {
        $flag = msg_receive($this->handle, 0, $msgtype, self::SYSVMSG_MAXSIZE, $msg, false, MSG_NOERROR);
        if (!$flag) {
            return false;
        }
        if ($msgtype == self::DAEMON_QUIT || $msgtype == self::DAEMON_RESTART) {
            return $msgtype;
        }
        return json_decode($msg, true);
    }

    /**
     * 往队列中添加一条通知任务执行进程退出的消息
     * @return bool 是否成功
     */
    public function notifyDaemonQuit()
    {
        return msg_send($this->handle, self::DAEMON_QUIT, 'QUIT', false, false);
    }

    /**
     * 往队列中添加一条通知任务执行进程重启的消息
     * @return bool 是否成功
     */
    public function notifyDaemonRestart()
    {
        return msg_send($this->handle, self::DAEMON_RESTART, 'RESTART', false, false);
    }
}