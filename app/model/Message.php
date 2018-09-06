<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/6
 * Time: 11:46
 */

namespace app\model;

use \Core\Model;

class Message extends Model
{

    public $table = 'message';

    public function list()
    {
        return $this->select($this->table, '*');
    }
}