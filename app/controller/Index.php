<?php
/**
 * Created by PhpStorm.
 * User: 85377
 * Date: 2018/9/5
 * Time: 14:54
 */

namespace app\controller;

use app\model\Guestbook;
use Core\Controller;

class Index extends Controller
{

    public function index()
    {
        $guestbookModel = new Guestbook();
        $r = $guestbookModel->getAll();
        $this->assign('data', $r);
        $this->display('index/index.html');
    }

    public function add()
    {
        $this->display('index/add.html');
    }

    public function save()
    {
        $data['title'] = post('title');
        $data['content'] = post('content');
        $data['createtime'] = time();
        $guestModel = new Guestbook();
        $ret = $guestModel->addOne($data);
        if ($ret) {
            $this->jump('/');
        } else {
            dump('error');
        }
    }

    public function del()
    {
        $id = get('id', 0, 'int');
        if ($id) {
            $guestModel = new Guestbook();
            $ret = $guestModel->delOne($id);
            if ($ret) {
                $this->jump('/');
            } else {
                throw new \Exception('delete error ' . $id);
            }
        } else {
            throw new \Exception('params error');
        }
    }
}