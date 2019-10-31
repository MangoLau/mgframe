<?php
/**
 * @author MangoLau
 */

namespace Frame\Core\Db;


use Frame\Core\Db;

class Mysql extends Db
{
    /**
     * @var $config
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = array_merge([
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => '',
            'pass' => '',
            'name' => ''
        ], $config);
        $this->connect();
    }

    /**
     * connect
     */
    public function connect()
    {
        extract($this->config);
        $dsn = "mysql:host={$host};port={$port};dbname={$name}";
        $this->instanceId = $dsn;
        if (PHP_SAPI == 'cli' && isset(parent::$pool[$dsn])) {
            $this->pdo = parent::$staticObjects[$dsn];
        } else {
            $options = array(
                \PDO::ATTR_PERSISTENT          =>  FALSE,
                \PDO::ATTR_EMULATE_PREPARES    =>  FALSE,
                \PDO::ATTR_ERRMODE             =>  \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE  =>  \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND  =>  "SET NAMES utf8mb4"
            );
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
            parent::$staticObjects[$dsn] = $this->pdo;
        }
    }
}