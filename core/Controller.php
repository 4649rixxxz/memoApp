<?php

namespace app\core;

abstract class Controller
{
  
  const PATH = '../views/';
  const LAYOUTDIR = 'layouts';

  //モデル
  public $model;
  //ビュー
  private $layoutContent;
  private $viewContent;

  /**
   * 最終的なビューを返す関数
   *
   * @param string $path
   * @param array $data
   * @return string
   */

  public function view($path,$data = [])
  {
    //ビューを取得
    $this->viewContent = $this->getView($path,$data);

    //レイアウトの宣言「@extends(レイアウトのファイル名(拡張子なし))」があるかどうか
    if(preg_match("/@extends(.*)/",$this->viewContent,$result)){
      //両端の「(」、「)」を削除し、レイアウトのファイル名を取得
      $fileName = str_replace(['(',')'],"",$result[1]);
      $this->layoutContent = $this->getLayout($fileName);
      $this->viewContent = str_replace($result[0],'',$this->viewContent);
    }

    //レイアウトの一部内容の置換
    $view = str_replace('{{ content }}',$this->viewContent,$this->layoutContent);

    //ワンタイムトークンが設置されているか確認
    $token = '{{ csrf_token }}';
    if(strpos($view,$token) !== false){
      //ワンタイムトークンタグの設置
      $result = "<input type='hidden' name='token' value='{$_SESSION['token']}'>";
      $view = str_replace($token,$result,$view);

      return $view;
    }

    return $view;

  }


  /**
   * ビューの取得
   *
   * @param string $path
   * @param array $data
   * @return boolean
   */

  private function getView($path,$data)
  {
    if(!empty($data)){
      //ビュー内で使う変数のセット
      foreach($data as $key => $value){
        $$key = $value;
      }
    }
    ob_start();
    include_once self::PATH.$path.".php";
    return ob_get_clean();
  }

  /**
   * レイアウトビューの取得
   *
   * @param string $path
   * @return boolean
   */

  private function getLayout($path)
  {
    ob_start();
    include_once "../views/layouts/".$path.".php";
    return ob_get_clean();
  }

  /**
   * パスワードのハッシュ化
   *
   * @param string $value
   * @return string
   */

  public function createHashedPassword($value)
  {
    
    return password_hash($value,PASSWORD_DEFAULT);

  }

}