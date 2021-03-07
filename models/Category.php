<?php

namespace app\models;

use app\core\Model;


class Category extends Model
{
  public function insert($user_id,$data)
  {
    $sql = "INSERT INTO categories (user_id,name) VALUES(:user_id,:cat_name)";

    $this->prepare($sql);
    $this->bind(':user_id',$user_id);
    $this->bind(':cat_name',$data['cat_name']);

    return $this->execute();
  }

  public function getCategories($user_id)
  {
    $sql = "SELECT * FROM categories WHERE user_id = {$user_id}";

    $this->query($sql);

    $data = $this->fetchAll(\PDO::FETCH_ASSOC);

    if($data !== false){
      return $data;
    }else{
      return [];
    }
  }

  public function findOne($user_id,$id)
  {
    $sql = "SELECT * FROM categories WHERE user_id = {$user_id} AND id = :id";

    

    $this->prepare($sql);
    $this->bind(":id",$id);

    $this->execute();

    $data = $this->fetch(\PDO::FETCH_ASSOC);

    if($data === false){
      die('不正なリクエスト');
    }
    
    return $data;
  }

  public function updateName($user_id,$id,$data)
  {
    $sql = "UPDATE categories SET name = :name WHERE user_id = {$user_id} AND id = :id";

    $this->prepare($sql);
    $this->bind(":name",$data['cat_name']);
    $this->bind(":id",$id);

    return $this->execute();
  }

  public function deleteName($user_id,$id)
  {
    $sql = "DELETE FROM categories WHERE user_id = {$user_id} AND id = :id";

    $this->prepare($sql);
    $this->bind(":id",$id);

    return $this->execute();
  }
}