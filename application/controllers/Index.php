<?php
/**
 * @author MangoLau
 */

use Frame\Core\Dao;

class IndexController extends Common_PublicController
{

    public function indexAction()
    {
        (new AsyncModel())->push(AsyncModel::CMD_TEST);
        echo 'done';
    }

    /**
     * 异步调用demo
     */
    public function asyncDemoAction()
    {
        (new AsyncModel())->push(AsyncModel::CMD_TEST);
        echo 'done';
    }
}