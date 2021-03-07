<?php

function getUrlRoot($goal = '')
{
  echo URLROOT.$goal;
}

function redirect($path = '')
{
  //遷移先
  $destination = 'Location:'.URLROOT.$path;
  header($destination);
  exit;
}

//エラー時およびデフォルトの値を取得
function getOldValue($key,$default = null)
{
  //エラー時の値
  if(isset($_SESSION[$key])){
    echo $_SESSION[$key];
    unset($_SESSION[$key]);
  }elseif(!isset($_SESSION[$key]) && isset($default)){
    echo $default;
  }else{
    echo "";
  }
}

//エラーメッセージがあるかどうか
function isErrMessage()
{
  if(array_key_exists('errorMessages',$_SESSION)){
    return true;
  }else{
    return false;
  }
}

//ファーストエラーメッセージの取得
function getFirstErrMessage($key)
{
  if(isErrMessage() && isset($_SESSION['errorMessages'][$key][0])){
    echo "<div class='alert alert-danger mt-3' role='alert'>{$_SESSION['errorMessages'][$key][0]}</div>";
    unset($_SESSION['errorMessages'][$key]);
  }
}

//フラッシュメッセージがあるかどうか
function isFlashMessage()
{
  if(array_key_exists('flash',$_SESSION)){
    return true;
  }else{
    return false;
  }
}

//フラッシュメッセージの取得
function getFlashMessage($key)
{
  if(isFlashMessage() && isset($_SESSION['flash'][$key])){
    echo "<div class='alert alert-success' role='alert'>{$_SESSION['flash'][$key]}</div>";
  }
}

function getLogoutMessage()
{
  if(isset($_SESSION['flash']['logout'])){
    getFlashMessage('logout');
    $_SESSION = [];
    session_destroy();
  }
}