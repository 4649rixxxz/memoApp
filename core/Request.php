<?php

namespace app\core;

use app\core\Session;
use app\models\User;

class Request
{

  public $postData;
  public $session;
  public $user;

  public function __construct()
  {
    //セッション開始
    $this->session = new Session();
    //Postメソッドの場合
    if($this->isPost()){
      //エスケープ処理
      $this->postData = $this->getEscapedData($_POST);
      // Session::start($this->postData,$this->getHttpMethod());
      $this->postData = $this->session->post($this->postData);
    }
    //ログインユーザオブジェクトの格納
    if(isset($_SESSION['user_id'])){
      $this->user = $this->getLoginUserModel();
    }
  }

  public function getPath()
  {
    //「/memoApp/」なら「/」
    $path = str_replace(APPROOT,'',$_SERVER['REQUEST_URI']);
   
    return $path;
  }

  public function getHttpMethod()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function isGet()
  {
    return $this->getHttpMethod() === 'get';
  }

  public function isPost()
  {
    return $this->getHttpMethod() === 'post';
  }

  public function getEscapedData($array)
  {
    $data = [];
    foreach($array as $key => $value){
      //改行処理
      $value = str_replace(PHP_EOL,"",$value);
      //特殊文字の処理
      $value = htmlspecialchars($value,ENT_QUOTES,'UTF-8');

      $data[$key] = $value;
    }

    return $data;
  }

  public function getLoginUserModel()
  {
    $user = new User;
    return $user->getUserData($_SESSION['user_id']);

  }

}