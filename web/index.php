<?php
define('ROOT', dirname(__DIR__));
require_once(__DIR__.'/../framework/Loader.php');
Loader::addNamespacePath('Blog\\',__DIR__.'/../src/Blog');
$loader = new Loader();
$loader->loadClass('/Controller/FrontController');
//$app = new \Framework\Application();
//$app = new \Framework\Application(__DIR__.'/../app/config/config.php');

//$app->run();

$routes = ROOT.'/app/config/routes.php';
$frontController = new FrontController($routes);
$frontController->run();