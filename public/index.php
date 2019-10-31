<?php

/**
 * @author MangoLau
 */
error_reporting(E_ALL & ~E_NOTICE);
define('ROOT_PATH', realpath(dirname(__FILE__).'/../'));
include ROOT_PATH . '/vendor/autoload.php';
$app = new Yaf\Application(ROOT_PATH.'/config/app.ini');
if (PHP_SAPI == 'cli') {
    $req = new \Yaf\Request\Simple();
    $app->bootstrap()->getDispatcher()->dispatch($req);
} else {
    $app->bootstrap()->run();
}