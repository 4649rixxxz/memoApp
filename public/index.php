<?php
//基本設定
require_once __DIR__.'/../config/config.php';
//ヘルパ関数
require_once __DIR__.'/../helpers/helper.php';
//オートローディング
require_once __DIR__.'/../vendor/autoload.php';

use app\core\Application;
use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\UserController;
use app\controllers\CategoryController;


$app = new Application();

$app->router->get('/',[HomeController::class,'index']);
$app->router->get('/login',[AuthController::class,'index']);
$app->router->post('/login',[AuthController::class,'login']);
$app->router->post('/logout',[AuthController::class,'logout']);
$app->router->get('/register',[AuthController::class,'register']);
$app->router->post('/store',[AuthController::class,'store']);
$app->router->get('/home',[CategoryController::class,'index']);
$app->router->get('/user/show',[UserController::class,'show']);
$app->router->get('/user/edit',[UserController::class,'edit']);
$app->router->post('/user/update',[UserController::class,'update']);

$app->router->get('/category/create',[CategoryController::class,'create']);
$app->router->post('/category/store',[CategoryController::class,'store']);
$app->router->get('/category/{id}/show',[CategoryController::class,'show']);
$app->router->post('/category/{id}/update',[CategoryController::class,'update']);
$app->router->post('/category/{id}/delete',[CategoryController::class,'delete']);



$app->run();
