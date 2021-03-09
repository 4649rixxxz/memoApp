<?php


namespace app\core;

use app\middlewares\AuthMiddleware;

class Router
{
  public $request;
  public $response;
  public $routes;
  
  public function __construct($request,$response)
  {
    //Requestインスタンスを格納
    $this->request = $request;
    //Responseインスタンスを格納
    $this->response = $response;
    
  }

  //GETメソッドに対応するコールバック関数
  public function get($path,$callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  //POSTメソッドに対応するコールバック関数
  public function post($path,$callback)
  {
    $this->routes['post'][$path] = $callback;
  }

  public function resolve()
  {
    //パスの取得
    $path = $this->request->getPath();
    //パラメータの獲得
    if($this->getParams($path)){
      $param = $this->getParams($path);
      //ルート配列のパス{*}をすべてパラメータに変換
      $this->routes = $this->changePathParam($this->routes,$param);
    }
    //httpメソッドの取得
    $method = $this->request->getHttpMethod();
    //URLに対応するコールバック関数の格納
    $callback = $this->routes[$method][$path] ?? false;



    //存在しないリクエスト
    if($callback === false){
      $this->response->setStatusCode(404);
      die('Not Found');
    }
    
    //第一引数にクラス、第二引数にメソッド
    if(is_array($callback)){
      //ミドルウェアの適用
      $middleware = new AuthMiddleware;
      $middleware->guard($callback[0]);
      //インスタンス化
      Application::$app->controller = new $callback[0]();
      //モデルのセット
      $model = $this->getModelClass($callback[0]);
      if(class_exists($model)){
        Application::$app->controller->model = new $model;
      }
      //インスタンス化したものを入れる
      $callback[0] = Application::$app->controller;
    }

    //callback[0]：オブジェクト、callback[1]：メソッド
    return call_user_func($callback,$this->request);
  }

  //コントローラのデフォルトのモデルクラスを取得する
  //「UserController」の場合「User」モデルとなる
  public function getModelClass($controller)
  {
    $controllerNamespace = "app\\controllers\\";
    //先頭から削除
    $controller = str_replace($controllerNamespace,"",$controller);
    //末尾のControllerを削除
    $modelClass = str_replace('Controller',"",$controller);
    //名前空間を追加
    $modelClass = "app\\models\\".$modelClass;

    return $modelClass;
  }


  private function getParams($path)
  {
    $explode = explode('/',$path);
    $param = false;

    foreach($explode as $value){
      //文字型の数字か数値型か
      if(is_numeric($value)){
        $param = $value;
        break;
      }
    }

    return $param;
  }


  private function changePathParam($routes,$param)
  {
    $result = [];
    $methods = ['get','post'];

    foreach($routes as $method => $pathArray)
    {
      if(in_array($method,$methods))
      {
        foreach($pathArray as $path => $callback)
        {
          if(preg_match('/\{.+\}/',$path,$target)){
            //{*}をパラメータに変換
            $path = str_replace($target[0],$param,$path);
            //変換後のパスに対応した関数の格納
            $result[$method][$path] = $callback;
            //空白および波括弧の削除
            $property = str_replace([" ","　","{","}"],"",$target[0]);
            //パラメータプロパティの格納
            if(!property_exists($this->request,$property)){
              //{id}の場合、requestオブジェクトにパラメータの値をプロパティidとして格納
              $this->request->{$property} = intval($param);
            }            
          }else{
            $result[$method][$path] = $callback;
          }
        }
      }
    }

    return $result;
  }


}