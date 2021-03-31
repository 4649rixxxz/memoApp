<?php

namespace app\core;

use app\exception\Handler;

class Session
{
  //エラーオブジェクト
  private $handler;
  //セッションタイムアウト時間の格納
  private $timeout = SESSION_TIMEOUT;

  public function __construct()
  {
    session_start();
    //エラーオブジェクトの格納
    $this->handler = new Handler;
    //セッションタイムアウト
    $this->isLoginTimeout();
    //ワンタイムトークンの作成
    $this->createToken();
  }

  //Postメソッドで取り扱うことのできないname属性
  private const bookedKeys = [
    'token',
    'timeout',
    'flash',
    'user_id'
  ];

  /**
   * POSTメソッドで送信されたデータをセッションに格納する
   * 
   * @param array $data
   */

  public function set($data)
  {
    //値のセット
    if(is_array($data)){
      foreach($data as $key => $value){
        //予約キーはスキップ
        if(!array_key_exists($key,self::bookedKeys)){
          $_SESSION[$key] = $value;
        }
      }
    }
  }

  /**
   * ワンタイムトークンを用いて正常なリクエストかどうかを確認
   *
   * @param array $data
   * @return array
   */

  public function isValidRequest($data)
  {
    //ワンタイムトークンの検索
    if(!array_key_exists('token',$data) || $data['token'] !== $_SESSION['token']){
      //「token」キーが存在しない場合もしくはトークンが一致しない場合、エラー表示
      $this->handler->output("不正なリクエストです");
    }elseif($data['token'] === $_SESSION['token']){
      //postデータのトークン削除
      unset($data['token']);
      //値のリセット
      unset($_SESSION['token']);

      return $data;
    }
  }


  /**
   * 現在時刻がタイムアウト時間より過ぎているかどうかを確認
   */

  private function isLoginTimeout()
  {
    if(isset($_SESSION['user_id']) && isset($_SESSION['timeout'])){
      //現在時刻の取得
      $now = time();
      //タイムアウト時間
      $timeout = $_SESSION['timeout'] + $this->timeout;
      //現在時刻がタイムアウト時間より過ぎているかどうか
      if($now > $timeout){
        //リダイレクトループを防ぐために削除
        unset($_SESSION['timeout']);
        //ログアウト
        unset($_SESSION['user_id']);
        $this->setFlashMessage('timeout','セッションタイムアウトにより、ログアウトしました。');
        redirect();
      }
    }
  }

  /** 
   * ワンタイムトークンの作成
  */
  private function createToken()
  {
    if(empty($_SESSION['token'])){
      $_SESSION['token'] = uniqid(bin2hex(random_bytes(1)));
    }
  }

  /** 
   * フラッシュメッセージの格納
  */
  public function setFlashMessage($key,$value)
  {
    $_SESSION['flash'][$key] = $value;
  }

  /**
   * セッションの破壊
   */

  public function destroy()
  {
    $_SESSION = [];
    session_destroy();
  }

  /**
   * ログイン処理
   *
   * @param string $id
   */

  public function setLoginUser($id)
  {
    //リセット
    $_SESSION = [];
    //現在時刻の格納
    $_SESSION['timeout'] = time();
    //ログイン
    $_SESSION['user_id'] = $id;
  }


  /**
   * 特定のセッション値の削除
   *
   * @param array $keyArray
   */

  public static function unsetValue($keyArray)
  {
    foreach($keyArray as $key => $value){
      unset($_SESSION[$key]);
    }
  }


  
}