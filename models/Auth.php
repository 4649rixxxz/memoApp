<?php

namespace app\models;

use app\core\Model;

class Auth extends Model
{
  //テーブル名
  const TABLE = 'users';
  //ユーザ登録
  public function insert($data)
  {
    $this->prepare(
      'INSERT INTO users (email,password,confirm_password) VALUES(:email,:password,:confirm_password)');
    
    //バインディング
    $this->bind(':email',$data['email']);
    $this->bind(':password',$data['password']);
    $this->bind(':confirm_password',$data['confirmPassword']);

    if($this->execute()){
      return true;
    }else {
      return false;
    }
  }
}