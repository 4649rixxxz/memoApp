<?php

namespace app\middlewares;

use app\controllers\CategoryController;
use app\controllers\MemoController;
use app\controllers\UserController;
use app\core\Middleware;

class AuthMiddleware extends Middleware
{
  private $loginStatus;
  private $classLists = [
    UserController::class,
    CategoryController::class,
    MemoController::class
  ];

  public function __construct()
  {
    //ログインしているかどうか
    $this->loginStatus = $_SESSION['user_id'] ?? false;
  }

  public function guard($class)
  {
   if($this->loginStatus === false && in_array($class,$this->classLists)){
    //ログインページへリダイレクト
    redirect('login');
   }
  }
}