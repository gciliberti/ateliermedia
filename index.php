<?php

require 'vendor/autoload.php';
require 'src/mf/utils/ClassLoader.php';
session_start();

$loader = new \mf\utils\ClassLoader('src');
$loader->register();
$array = parse_ini_file("conf/config.ini");

\app\view\AppView::addStyleSheet("html/css/style.css");
\app\view\AppView::addStyleSheet("html/css/bootstrap.css");
\app\view\AppView::addStyleSheet("html/css/bootstrap-grid.css");

$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($array);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();

$router->addRoute('home',
                  '/home/',
                  '\app\control\AppController',
                  'viewHome',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);


$router->setDefaultRoute('/home/');


$router->run();

//echo $router->urlFor("maison",["id"=>5,"author"=>4,"test"=>6]);

/* Après exécution de cette instruction, l'attribut statique $routes et
   $aliases de la classe Router auront les valeurs suivantes: */
