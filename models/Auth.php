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
      'INSERT INTO users (email,password) VALUES(:email,:password)');
    
    //バインディング
    $this->bind(':email',$data['email']);
    $this->bind(':password',$data['password']);

    if($this->execute()){
      return true;
    }else {
      return false;
    }
  }

  /*
    @return 
  */

  public function findUser($email)
  {
    $sql = "SELECT * FROM users WHERE email = :email";
    
    $this->prepare($sql);
    $this->bind(':email',$email);

    if($this->execute()){
      $user = $this->fetch(\PDO::FETCH_ASSOC);

      return $user;
    }else{
      return false;
    }
    
  }
}