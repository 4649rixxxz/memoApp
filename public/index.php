<?php
//基本設定
require_once __DIR__.'/../config/config.php';
//オートローディング
require_once __DIR__.'/../vendor/autoload.php';

use app\core\Application;
use app\controllers\HomeController;
use app\controllers\AuthController;


$app = new Application();

$app->router->get('/',[HomeController::class,'index']);
$app->router->get('/login',[AuthController::class,'login']);
$app->router->get('/register',[AuthController::class,'register']);
$app->router->post('/register',[AuthController::class,'register']);

$app->run();
