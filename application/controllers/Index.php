<?php
/**
 * @author MangoLau
 */

use Frame\Core\Dao;

class IndexController extends Common_PublicController
{

    public function indexAction()
    {
        throw new \Api\Exception\CallMethodInvalidException('CallMethodInvalidException');
        $dao = new Dao('admin');
        $ret = $dao->limit(5)->select();
        return $this->json(0, '', $ret);
    }
}