<?php

namespace app\core;


class Session
{

  public function __construct()
  {
    session_start();
    //ワンタイムトークンの作成
    $this->createToken();
  }

  private const ExistingKeys = [
    'token'
  ];

  
  public function set($data)
  {
    if(is_array($data)){
      foreach($data as $key => $value){
        //予約キーはスキップ
        if(!array_key_exists($key,self::ExistingKeys)){
          $_SESSION[$key] = $value;
        }
      }
    }
  }

  public function post($data)
  {
    //再発行
    session_regenerate_id();
    //値のセット
    $this->set($data);
    //ワンタイムトークンの検索
    if(!array_key_exists('token',$data) || $data['token'] !== $_SESSION['token']){
      //「token」キーが存在しない場合もしくはトークンが一致しない場合、エラー表示
      die('不正なリクエストです');
    }elseif($data['token'] === $_SESSION['token']){
      //値のリセット
      unset($_SESSION['token']);
    }
  }

  public function createToken()
  {
    //ワンタイムトークンの作成
    if(empty($_SESSION['token'])){
      $_SESSION['token'] = uniqid(bin2hex(random_bytes(1)));
    }
  }

  public static function setFlashMessage($key,$value)
  {
    $_SESSION['flash'][$key] = $value;
  }

  public static function destroy()
  {
    $_SESSION = [];
    session_destroy();
  }
}