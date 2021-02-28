<?php

namespace app\core;

use app\core\Session;


class Request
{

  public $postData;
  public $session;

  public function __construct()
  {
    //セッション開始
    $this->session = new Session();
    if($this->isPost()){
      //エスケープ処理
      $this->postData = $this->getEscapedData($_POST);
      // Session::start($this->postData,$this->getHttpMethod());
      $this->session->post($this->postData);
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

}