<?php

namespace app\core;


class Request
{
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

}