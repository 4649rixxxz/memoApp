<?php

namespace app\core;

use app\core\Session;
use app\models\User;

class Request
{

  public $postData;
  public $session;

  public function __construct()
  {
    //セッション開始
    $this->session = new Session();
    //Postメソッドの場合
    if($this->isPost()){
       //再発行
      session_regenerate_id();
      //エスケープ処理
      $this->postData = $this->getEscapedData($_POST);
      //ワンタイムトークンを用いて正常なリクエストかどうかを確認
      $this->postData = $this->session->isValidRequest($this->postData);
      //セッションに値を格納
      $this->session->set($this->postData);
    }

  }

  /**
   * 定数APPROOT(/memoApp)以下のパスを取得
   *
   * @return string
   */

  public function getPath()
  {
    //「/memoApp/」なら「/」
    $path = str_replace(APPROOT,'',$_SERVER['REQUEST_URI']);
   
    return $path;
  }

  /**
   * Httpメソッドを取得し、小文字に変換
   * GETの場合、get
   *
   * @return void
   */
  public function getHttpMethod()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  /**
   * GETメソッドであるかどうか
   *
   * @return boolean
   */

  public function isGet()
  {
    return $this->getHttpMethod() === 'get';
  }

  /**
   * POSTメソッドであるかどうか
   *
   * @return boolean
   */

  public function isPost()
  {
    return $this->getHttpMethod() === 'post';
  }

  /**
   * POSTで送信されたデータのサニタイズ
   *
   * @param array $array
   * @return array
   */
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

  /**
   * ログインユーザーのアカウント情報を取得
   *
   * @return object
   */
  public function auth()
  {
    //ログインユーザオブジェクトの格納
    if(isset($_SESSION['user_id'])){
      $user = new User;
      return $user->getUserData($_SESSION['user_id']);
    }
  }

}