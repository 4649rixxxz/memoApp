<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
  
  public $id;
  public $name;
  public $email;
  public $created_at;
  public $updated_at;


  public function getUserData($id)
  {
   
    $sql = "SELECT * FROM users WHERE id = {$id}";

    $this->query($sql);    
    $userData = $this->fetch(\PDO::FETCH_ASSOC);

    
    // Ex.)$this->id = $userData['id'];
    if($userData !== false){

      foreach($userData as $key => $value){
        if(property_exists($this,$key)){
          $this->{$key} = $value;
        }
      }
  
      return $this;
    }


  }

  public function update($id,$data)
  {
    
   $sql = "UPDATE users SET email = :email,password = :password WHERE id = {$id}";
   
   $this->prepare($sql);
   $this->bind(':email',$data['email']);
   $this->bind(':password',$data['password']);

   return $this->execute();

  }

  public function delete($id)
  {
    $sql = "DELETE FROM users WHERE id = {$id}";

    if($this->query($sql) !== false){
      return true;
    }else{
      return false;
    }


  }

}