<?php

/**
 * Index
 */
class IndexController extends Admin_ProtectedController
{
	/**
	 * index
	 */
    public function indexAction()
    {
        $vars = [
            'layout_title' => '首页',
            'layout_desc' => '',
            'layout_css' => [
                'adminlte/bower_components/morris.js/morris.css',
            ],
            'layout_script' => [
                'adminlte/bower_components/raphael/raphael.min.js',
                'adminlte/bower_components/morris.js/morris.min.js',
            ],
        ];
        return $this->layout('welcome', $vars);
    }
}
