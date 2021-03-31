<?php

namespace app\models;

use app\core\Model;


class Category extends Model
{
  /**
   * カテゴリーの新規登録
   *
   * @param string $user_id
   * @param array $data
   * @return boolean
   */

  public function insert($user_id,$data)
  {
    $sql = "INSERT INTO categories (user_id,name) VALUES(:user_id,:cat_name)";

    $this->prepare($sql);
    $this->bind(':user_id',$user_id,\PDO::PARAM_INT);
    $this->bind(':cat_name',$data['cat_name']);

    return $this->execute();
  }


  /**
   * ログインユーザの全カテゴリーを取得
   *
   * @param string $user_id
   * @return array
   */

  public function get($user_id)
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


  /**
   * ログインユーザが持つ特定のカテゴリーの取得
   * 
   * @param string $user_id
   * @param string $id
   * @return array
   */


  public function find($user_id,$id)
  {
    $sql = "SELECT * FROM categories WHERE user_id = {$user_id} AND id = :id";

    

    $this->prepare($sql);
    $this->bind(":id",$id,\PDO::PARAM_INT);

    $this->execute();

    $data = $this->fetch(\PDO::FETCH_ASSOC);

    if($data === false){
      $this->handler->output("不正なリクエストです");
    }
    
    return $data;
  }


  /**
   * ログインユーザのカテゴリの更新
   *
   * @param string $user_id
   * @param string $id
   * @param array $data
   * @return boolean
   */

  public function update($user_id,$id,$data)
  {
    //該当レコードが存在するか
    if($this->find($user_id,$id)){
      $sql = "UPDATE categories SET name = :name,updated_at = :updated_at WHERE user_id = {$user_id} AND id = :id";
  
      $this->prepare($sql);
      $this->bind(":name",$data['cat_name']);
      $this->bind(":updated_at",$this->today);
      $this->bind(":id",$id,\PDO::PARAM_INT);
  
      return $this->execute();
    }
  }


  /**
   * ログインユーザが持つカテゴリの削除
   *
   * @param string $user_id
   * @param string $id
   * @return boolean
   */
  
  public function delete($user_id,$id)
  {
    if($this->find($user_id,$id)){
      $sql = "DELETE FROM categories WHERE user_id = {$user_id} AND id = :id";

      $this->prepare($sql);
      $this->bind(":id",$id,\PDO::PARAM_INT);

      return $this->execute();
    }
  }

}