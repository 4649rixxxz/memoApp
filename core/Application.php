<?php


namespace app\core;

use app\core\Request;
use app\core\Response;
use app\core\Router;

class Application
{
  public static $app;
  public $request;
  public $response;
  public $router;
  public $controller;


  public function __construct()
  {
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request,$this->response);


  }

  public function run()
  {
    echo $this->router->resolve();
  }



}