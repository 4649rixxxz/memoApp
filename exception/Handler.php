<?php

namespace app\exception;


class Handler
{
  //エラー内容の表示
  public function output($message)
  {
    echo $this->getFormat($message); 
    die;
  }


  private function getFormat($message)
  {
    // ユーザのホーム画面へ
    $path = getUrlRoot('home');

    return "
      <div class=''>
        <h1>$message</h1>
        <a href='$path'>ホーム画面へ戻る</a>
      </div>
    ";
  }

}