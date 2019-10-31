<?php
/**
 * @author MangoLau
 */

namespace Frame\Core;


use mysql_xdevapi\Exception;

abstract class Db
{

    const FETCH_COL = 1;
    const FETCH_ROW = 2;
    const FETCH_ALL = 3;

    /**
     * @var \PDO $pdo
     */
    protected $pdo;

    /**
     * 实例的ID
     * @var string
     */
    protected $instanceId;

    /**
     * 是否禁止使用静态换成对象
     * @var bool
     */
    public static $disableStaticObjects = false;

    /**
     * PDO 连接池
     * @var array \PDO
     */
    public static $staticObjects = [];

    /**
     * @var int
     */
    protected $affectedRows = 0;

    /**
     * destruct
     */
    public function __destruct()
    {
        if (isset(self::$staticObjects[$this->instanceId]) && self::$disableStaticObjects) {
            unset(self::$staticObjects[$this->instanceId]);
        }
        unset($this->pdo);
    }

    /**
     * check sql
     * @param $sql
     * @param $params
     */
    public function check($sql, $params)
    {
        if (false !== stripos($sql, 'where') && empty($params)) {
            throw new Exception('You should use PDO bind param instead of pass directely !');
        }
    }

    /**
     * query
     * @param $sql
     * @param array $params
     * @param int $mode
     * @param int $col
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function query($sql, $params = [], $mode = 0, $col = 0)
    {
        $this->check($sql, $params);
        try {
            $stmt = $this->pdo->prepare($sql);
            $ret = $stmt->execute($params);
        } catch (\PDOException $e) {
            if ($e->getCode() == 'HY000' && $e->errorInfo[1] == 2006) {
                $this->connect();
                $stmt = $this->pdo->prepare($sql);
                $ret = $stmt->execute($params);
            } else {
                throw $e;
            }
        }
        switch ($mode) {
            case self::FETCH_COL :
                $ret = $stmt->fetchColumn($col);
                break;
            case self::FETCH_ROW :
                $ret = $stmt->fetch();
                break;
            case self::FETCH_ALL :
                $ret = $stmt->fetchAll();
                break;
            default :
                break;
        }
        if ($mode > 0) {
            $stmt->closeCursor();
            $this->affectedRows = 0;
        } else {
            $this->affectedRows = $stmt->rowCount();
        }
        unset($stmt);
        return $ret;
    }

    /**
     * get last insert id.
     * @param null $name
     * @return string
     */
    public function getLastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * get affected rows.
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * begin transaction.
     * @return bool
     */
    public function beginTrans()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * commit
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * roll back
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * keep pdo connection alive
     */
    public static function keepAlive()
    {
        if (PHP_SAPI != 'cli') {
            return;
        }
        foreach (self::$staticObjects as $id => $inst) {
            $stmt = $inst->query("SELECT 1");
            unset($stmt);
        }
    }
}