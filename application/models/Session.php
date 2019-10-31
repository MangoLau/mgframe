<?php
use Frame\Core\MG;

/**
 * 会话模型类
 */
class SessionModel
{
    /**
     * 会话有效期
     */
    const EXPIRE = 86400;

    /**
     * 生成会话ID
     * @param $uid
     * @return string
     */
    public function makeSessonId($uid)
    {
        $now = time();
        $rand = rand(0, 999999999);
        return md5("sess|{$uid}|{$now}|{$rand}");
    }

    /**
     * 生成Session数据的KEY
     * @param $sess
     * @return string
     */
    public function key($sess)
    {
        return "SESS|{$sess}";
    }

    /**
     * 生成uid与session_id映射关系的key
     * @param $uid
     * @return string
     */
    public function mapKey($uid)
    {
        return "SESS_MAP|{$uid}";
    }

    /**
     * 设置用户的会话数据
     * @param $uid 用户ID
     * @param $data 会话数据
     * @return bool|string 成功返回会话ID，失败返回false
     */
    public function set($uid, $data)
    {
        if ($uid < 1 || !is_array($data) || empty($data)) {
            return false;
        }
        $sess = $this->makeSessonId($uid);
        $key = $this->key($sess);
        $mapKey = $this->mapKey($uid);
        $redis = MG::redis('session');
        $flag = $redis->hMSet($key, $data);
        if ($flag === false) {
            return false;
        }
        $redis->set($mapKey, $sess);
        $redis->expire($key, self::EXPIRE);
        $redis->expire($mapKey, self::EXPIRE);
        return $sess;
    }

    /**
     * 取会话数据
     * @param $sess 会话ID
     * @return bool|array 成功返回会话数据，失败返回false
     */
    public function get($sess)
    {
        if (empty($sess)) {
            return false;
        }
        $key = $this->key($sess);
        return MG::redis('session')->hGetAll($key);
    }

    /**
     * 更新会话数据的有效期
     * @param $sess 会话ID
     * @return bool 是否成功
     */
    public function touch($sess)
    {
        if (empty($sess)) {
            return false;
        }
        $key = $this->key($sess);
        return MG::redis('session')->expire($key, self::EXPIRE);
    }

    /**
     * 删除会话数据
     * @param $sess 会话ID
     * @return bool 是否成功
     */
    public function del($sess)
    {
        if (empty($sess)) {
            return false;
        }
        $key = $this->key($sess);
        return MG::redis('session')->del($key, self::EXPIRE);
    }

    /**
     * 查询用户的会话ID
     * @param $uid 用户ID
     * @return bool|string 成功返回会话ID，失败或无数据时返回false
     */
    public function querySessionId($uid)
    {
        if ($uid < 1) {
            return false;
        }
        $mapKey = $this->mapKey($uid);
        return MG::redis('session')->get($mapKey);
    }
}