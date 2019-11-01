<?php
use Frame\Core\Dao;
use Frame\Core\MG;

class System_GlobalsettingModel extends Dao
{
    /**
     * 空数据
     * @var integer
     */
    const EMPTY = 0;

    /**
     * 格式：原样
     * @var integer
     */
    const RAW = 1;

    /**
     * 格式：JSON编码
     * @var integer
     */
    const JSON = 2;

    /**
     * DB
     * @var string
     */
    protected $dbalias = 'system';
    /**
     * Redis配置文件中的名称
     * @var string
     */
    protected $redisName = 'system';
    
    /**
     * 所有的KEY列表(只能用小写，长度不能超过32个字符)
     * @var array
     */
    protected $keys = [
        'demo',// demo
    ];

    /**
     * 设置缓存
     * @param string $key
     * @param int $expire
     * @param array $values
     */
    protected function setCache($key, $values, $expire=86400)
    {
        $redis = MG::redis($this->redisName);
        $redis->hMset($key, $values);
        if ($expire) {
            $redis->expire($key, $expire);
        }
    }

    /**
     * 取缓存数据
     * @param string $key
     * @return array|bool 成功返回数据，失败返回false
     */
    protected function getCache($key)
    {
        return MG::redis($this->redisName)->hGetAll($key);
    }

    /**
     * 删除缓存数据
     * @param string $key
     * @return bool
     */
    protected function delCache($key)
    {
        return MG::redis($this->redisName)->del($key);
    }

    /**
     * 设置数据
     * @param string $key
     * @param mixed $val
     * @param int $format
     * @return boolean
     */
    public function set($key, $val, $format=self::RAW)
    {
        $key = strtolower($key);
        if (!in_array($key, $this->keys)) {
            return false;
        }
        switch ($format) {
            case self::EMPTY:
                return false; //空格式仅用于生成记录不存在的缓存数据，要清空数据直接删除
            case self::RAW:
                break;
            case self::JSON:
                $val = json_encode($val);
                break;
            default:
                return false;
        }
        $params = [$key, $val, $format, $val, $format];
        $sql = "INSERT INTO {$this->table}(k,v,vformat) VALUES(?,?,?) ON DUPLICATE KEY UPDATE v=?,vformat=?";
        $flag = $this->query($sql, $params);
        if ($flag) {
            $this->setCache($key, ['v' => $val, 'vformat' => $format]);
        }
        return $flag;
    }

    /**
     * 取数据
     * @param string $key
     * @return boolean|mixed
     */
    public function get($key)
    {
        $key = strtolower($key);
        if (!in_array($key, $this->keys)) {
            return false;
        }
        $record = $this->getCache($key);
        if (!$record) {
            $record = $this->fields(['v', 'vformat'])->where('k', $key)->getOne();
            if (!$record) {
                $record = ['v' => '', 'vformat' => self::EMPTY];
            }
            $this->setCache($key, $record);
        }
        switch (intval($record['vformat'])) {
            case self::EMPTY:
                return null;
            case self::RAW:
                return $record['v'];
            case self::JSON:
                return json_decode($record['v'], true);
            default:
                return false;
        }
    }

    /**
     * 删除数据
     * @param $key
     * @return array|bool
     * @throws Exception
     */
    public function del($key)
    {
        $flag = $this->where('k', $key)->delete();
        if ($flag) {
            $this->delCache($key);
        }
        return $flag;
    }
}