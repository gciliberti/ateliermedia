<?php

require 'vendor/autoload.php';
require 'src/mf/utils/ClassLoader.php';
session_start();

$loader = new \mf\utils\ClassLoader('src');
$loader->register();
$array = parse_ini_file("conf/config.ini");

//\app\view\AppView::addStyleSheet("html/css/style.css");
//\app\view\AppView::addStyleSheet("html/css/bootstrap.css");
//\app\view\AppView::addStyleSheet("html/css/bootstrap-grid.css");
\app\view\AppView::addStyleSheet("html/css/main.css");


$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($array);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();

$router->addRoute('home',
'/home/',
'\app\control\AppController',
'viewHome',\app\auth\AppAuthentification::ACCESS_LEVEL_USER);

$router->addRoute('login',
'/login/',
'\app\control\AppAuthController',
'login',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checklogin',
'/checklogin/',
'\app\control\AppAuthController',
'checkLogin',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('register',
'/register/',
'\app\control\AppAuthController',
'register',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

$router->addRoute('checkregister',
'/checkregister/',
'\app\control\AppAuthController',
'checkRegister',\app\auth\AppAuthentification::ACCESS_LEVEL_NONE);

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

$router->addRoute('logout',
'/logout/',
'\app\control\AppAuthController',
'logout',\app\auth\AppAuthentification::ACCESS_LEVEL_USER);





$router->setDefaultRoute('/home/');


$router->run();
