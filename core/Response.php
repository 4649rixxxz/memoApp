<?php

namespace app\core;


class Response
{
  public $test = 'test';
  public function setStatusCode($code)
  {
    http_response_code($code);
  }
}