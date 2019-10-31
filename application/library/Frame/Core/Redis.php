<?php
/**
 * @author MangoLau
 */

namespace Frame\Core;


class Redis
{
    /**
     * @var \Redis
     */
    protected $rds;

    /**
     * 实例ID
     * @var
     */
    protected $instanceId;

    /**
     * 是禁止使用静态缓存对象
     * @var bool
     */
    public static $disableStaticObjects = false;

    /**
     * 连接池
     * @var array \Redis
     */
    public static $staticObjects = [];

    public $config;

    public function __construct($config)
    {
        $this->config = array_merge([
            'host' => '127.0.0.1',
            'port' => '6379',
            'pconnect' => false,
            'timeout'  => 0.5
        ], $config);
        $this->connect();
    }

    public function __destruct()
    {
        if (isset(self::$staticObjects[$this->instanceId]) && self::$disableStaticObjects) {
            unset(self::$staticObjects[$this->instanceId]);
        }
    }

    /**
     * connect
     */
    public function connect()
    {
        $this->instanceId = "{$this->config['password']}@{$this->config['host']}:{$this->config['port']}#{$this->config['database']}";
        try {
            if (PHP_SAPI == 'cli' && isset(self::$staticObjects[$this->instanceId])) {
                $this->rds = self::$staticObjects[$this->instanceId];
            } else {
                if ($this->rds) {
                    unset($this->rds);
                }
                $this->rds = new \Redis();
                if ($this->config['pconnect']) {
                    $this->rds->pconnect($this->config['host'], $this->config['port'], $this->config['timeout']);
                } else {
                    $this->rds->connect($this->config['host'], $this->config['port'], $this->config['timeout']);
                }
                if (!empty($this->config['password'])) {
                    $this->rds->auth($this->config['password']);
                }
                if (!empty($this->config['database'])) {
                    $this->rds->select($this->config['database']);
                }
                if (PHP_SAPI == 'cli') {
                    self::$staticObjects[$this->instanceId] = $this->rds;
                }
            }
        } catch (\RedisException $e) {
            MG::log()->error($e->getMessage());
        }
    }

    /**
     * __call
     * @param $method
     * @param $args
     * @return bool|mixed
     * @throws \RedisException
     */
    public function __call($method, $args)
    {
        $reConnect = false;
        while (1) {
            try {
                $result = call_user_func_array(array($this->rds, $method), $args);
            } catch (\RedisException $e) {
                //已重连过，仍然报错
                if ($reConnect) {
                    throw $e;
                }
                MG::log()->error($e->getMessage());
                if ($this->rds->isConnected()) {
                    $this->rds->close();
                }
                $this->connect();
                $reConnect = true;
                continue;
            }
            return $result;
        }
        //不可能到这里
        return false;
    }

    /**
     * CLI模式下保持所有实例的连接
     */
    public static function keepAlive()
    {
        if (PHP_SAPI != 'cli') {
            return;
        }
        foreach (self::$staticObjects as $id => $inst) {
            $inst->ping();
        }
    }
}