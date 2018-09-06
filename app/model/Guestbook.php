<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/6
 * Time: 11:46
 */

namespace app\model;

use \Core\Model;

class Guestbook extends Model
{

    public $table = 'guestbook';

    /**
     * get All
     * @return array|bool
     */
    public function getAll()
    {
        return $this->select($this->table, '*');
    }

    /**
     * add one
     * @param $data
     * @return bool|\PDOStatement
     */
    public function addOne($data)
    {
        return $this->insert($this->table, $data);
    }

    /**
     * del one by id
     * @param $id
     * @return bool|\PDOStatement
     */
    public function delOne($id)
    {
        return $this->delete($this->table, ['id' => intval($id)]);
    }
}