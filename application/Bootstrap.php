<?php

/**
 * bootstrap
 * @author MangoLau
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{
	/**
	 * init config
	 */
    public function _initConfig()
    {
        Yaf\Registry::set('config', Yaf\Application::app()->getConfig());
    }

    /**
     * init plugin
     *
     * @param Yaf\Dispatcher $dispatcher
     */
    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        //$auth = new AuthPlugin();
        //$dispatcher->registerPlugin($auth);
    }

    /**
     * init route
     *
     * @param Yaf\Dispatcher $dispatcher
     */
    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
		$routes = new Yaf\Config\Ini(ROOT_PATH.'/config/routes.ini');
		$dispatcher->getRouter()->addConfig($routes);
    }
}