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
    return $this->view('index');
  }
}