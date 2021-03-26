<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
  /**
   * ログインユーザのアカウント情報の取得
   *
   * @param string $id
   * @return object
   */

  public function getUserData($id)
  {
   
    $sql = "SELECT * FROM users WHERE id = {$id}";

    if($this->query($sql)){

      $userData = $this->fetch(\PDO::FETCH_ASSOC);
      // カラム名と同じ名前のプロパティをセットする
      if($userData !== false){
  
        foreach($userData as $key => $value){
          if(!property_exists($this,$key)){
            $this->{$key} = $value;
          }
        }
    
        return $this;
      }
    }    

  }

  /**
   * ログインユーザのアカウント情報の更新
   * 
   * @param string $id
   * @param array $data
   * @return boolean
   */

  public function update($id,$data)
  {
    
   $sql = "UPDATE users SET email = :email,password = :password WHERE id = {$id}";
   
   $this->prepare($sql);
   $this->bind(':email',$data['email']);
   $this->bind(':password',$data['password']);

   return $this->execute();

  }

  /**
   * ログインユーザのアカウント削除
   *
   * @param string $id
   * @return boolean
   */

  public function delete($id)
  {
    $sql = "DELETE FROM users WHERE id = {$id}";

    return $this->query($sql);

  }

}