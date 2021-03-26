<?php

namespace app\core;


class Response
{
  public $test = 'test';

  /**
   * httpステータスの格納
   *
   * @param int $code
   */

  public function setStatusCode($code)
  {
    http_response_code($code);
  }
}