<?php

/**
 * 后台账号管理、权限管理等超级管理员功能的基类，所有管理后台账号、权限等超级管理员才能访问的页面或接口请继承本类
 * @author MangoLau
 *
 */
class Admin_PrivateController extends Admin_ProtectedController
{
    /**
     * 初始化方法，验证管理员权限
     */
    public function init()
    {
        parent::init(); //行去执行父类的同名函数
        //只允许超级管理员访问
        if (!$this->supeUser) {
            throw new \Api\Exception\AccessDeniedException('forbidden', 403);
        }
    }
}