<?php

namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller
{
  /**
   * ユーザーのホーム画面ではなく、アプリのホーム画面の表示
   *
   * @return string
   */

  public function index()
  {
    $data = ['name' => '田中'];
    return $this->view('index',$data);
  }
}