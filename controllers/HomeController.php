<?php

namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller
{
  public function index()
  {
    $data = ['name' => '田中'];
    return $this->view('index',$data);
  }
}