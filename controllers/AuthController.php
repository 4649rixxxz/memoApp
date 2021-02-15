<?php

namespace app\controllers;

use app\core\Controller;

class AuthController extends Controller
{

  public $method;

  public function login()
  {
    return $this->view('auth/login');
  }

  public function register()
  {
    if($this->method === 'get'){
      return $this->view('auth/register');
    }

    if($this->method === 'post'){
      var_dump($_POST);
      exit;
    }
  }
}