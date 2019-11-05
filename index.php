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

$router->addRoute('login',
'/login/',
'\app\control\AppController',
'viewLogin',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('register',
'/register/',
'\app\control\AppController',
'viewRegister',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('borrow',
'/borrow/',
'\app\control\AppController',
'viewBorrow',\app\auth\AppAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('profile',
'/profile/',
'\app\control\AppController',
'viewProfile',\app\auth\AppAuthentification::ACCESS_LEVEL_USER);


$router->addRoute('view',
'/view/',
'\app\control\AppController',
'viewMedia',\app\auth\AppAuthentification::ACCESS_LEVEL_USER);






$router->setDefaultRoute('/home/');


$router->run();

//echo $router->urlFor("maison",["id"=>5,"author"=>4,"test"=>6]);

/* Après exécution de cette instruction, l'attribut statique $routes et
$aliases de la classe Router auront les valeurs suivantes: */
