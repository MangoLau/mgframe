<?php

define('ROOT_PATH', realpath('../'));
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . DS . 'app');
define('CORE_PATH', ROOT_PATH . DS . 'Core');
define('MODULE', 'app');

define('DEBUG', true);

include CORE_PATH . DS . 'Common/function.php';

include (ROOT_PATH . DS . 'vendor/autoload.php');

if (DEBUG) {
    $whoops = new \Whoops\Run;
    $option = new \Whoops\Handler\PrettyPageHandler();
    $option->setPageTitle('系统开小差啦！');
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
	ini_set('display_error', 'On');
} else {
	ini_set('display_error', 'Off');
}

Core\Log::init();

Core\Mg::run();