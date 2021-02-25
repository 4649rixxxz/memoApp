<?php

namespace app\core;


class Session
{
  private const ExistingKeys = [
    'token'
  ];

  const GET_METHOD_LIST = [
    '/register',
    '/login'
  ];

  public static function start($data = [],$method = '')
  {
    //セッション開始
    session_start();
    //データの格納
    if(count($data) > 0){
      foreach($data as $key => $value){
        //既存のキーはスキップ
        if(!array_key_exists($key,self::ExistingKeys)){
          $_SESSION[$key] = $value;
        }
      }
    }
    //ワンタイムトークンの作成
    $_SESSION['token'] = uniqid(bin2hex(random_bytes(1)));
    //postの場合
    if($method === 'post'){
      //再発行
      session_regenerate_id();
      //ワンタイムトークンの検索
      if(!array_key_exists('token',$data) || $data['token'] !== $_SESSION['token']){
        die('不正なリクエストです');
      }

    }
  }

  public static function setFlashMessage($key,$value)
  {
    $_SESSION['flash'][$key] = $value;
  }
}