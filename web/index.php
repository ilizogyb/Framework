<?php
require_once(__DIR__.'/../framework/Loader.php');

$loader = new Loader();
$loader->addNamespacePath('Blog\\',__DIR__.'/../src/Blog');
$loader->addNamespacePath('Framework\\',__DIR__.'/../framework');
$loader->register();

$app = new \Framework\Application(__DIR__.'/../app/config/config.php');
$app->run();