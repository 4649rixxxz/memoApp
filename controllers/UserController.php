<?php

namespace app\controllers;

use app\core\Controller;

class UserController extends Controller
{

  public function index()
  {
    return $this->view('users/home');
  }
  
}