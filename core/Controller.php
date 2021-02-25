<?php

namespace app\core;

abstract class Controller
{
  
  const PATH = '../views/';
  const LAYOUTDIR = 'layouts';

  public $layoutContent;
  public $viewContent;

  public function view($path,$data = [])
  {
    //ビューを取得
    $this->viewContent = $this->getView($path,$data);

    $token = '{{ csrf_token }}';
    //ワンタイムトークンが設置されているか確認
    if(strpos($this->viewContent,$token) !== false){
      //ワンタイムトークンタグの設置
      $result = "<input type='hidden' name='token' value='{$_SESSION['token']}'>";
      $this->viewContent = str_replace($token,$result,$this->viewContent);
    }

    //ファイル名を取得
    $fileName = $this->getFileName();

    //レイアウト検索
    foreach($fileName as $value){

      $text = '@extends('.$value.')';

      //レイアウト継承宣言がある場合
      if(strpos($this->viewContent,$text) !== false){
        $this->layoutContent = $this->getLayout($value);
        $this->viewContent = str_replace($text,'',$this->viewContent);
        break;
      }
    }


    return str_replace('{{ content }}',$this->viewContent,$this->layoutContent);

  }


  public function getView($path,$data)
  {
    foreach($data as $key => $value){
      $$key = $value;
    }
    ob_start();
    include_once self::PATH.$path.".php";
    return ob_get_clean();
  }


  public function getLayout($path)
  {
    ob_start();
    include_once "../views/layouts/".$path.".php";
    return ob_get_clean();
  }


  public function getFileName()
  {
    $path = self::PATH.self::LAYOUTDIR.'/';
    $nameArray = glob($path.'*.php');

    foreach($nameArray as $key => $value){
      $nameArray[$key] = str_replace($path,'',$value);
    }

    foreach($nameArray as $key => $value){
      $nameArray[$key] = str_replace('.php','',$value);
    }

    return $nameArray;

  }


  //パスワードのハッシュ化
  public function createHashedPassword($data,$keyLists)
  {
    foreach($keyLists as $key){
      //キーが存在するか
      if(array_key_exists($key,$data)){
        $data[$key] = password_hash($data[$key],PASSWORD_DEFAULT);
      }
    }

    return $data;
  }

}