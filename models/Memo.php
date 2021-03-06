<?php

namespace app\models;

use app\core\Model;

class Memo extends Model
{
  /**
   * メモが持つカテゴリが存在するか、すなわち不正なパラメータによるリクエストではないかを検証
   *
   * @param string $category_id
   * @param string $user_id
   * @return boolean
   */

  public function findCategory($category_id,$user_id)
  {
    $sql = "SELECT * FROM categories WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':id',$category_id,\PDO::PARAM_INT);

    if($this->execute()){

      $data = $this->fetch(\PDO::FETCH_ASSOC);
  
      if($data === false){
         //パラメータが不正な場合
         $this->handler->output("不正なリクエスト");
      }
  
      return $data;
    }

  }


  /**
   * メモの新規登録
   *
   * @param string $user_id
   * @param string $category_id
   * @param array $data
   * @return boolean
   */

  public function insert($user_id,$category_id,$data)
  {
    if($this->findCategory($category_id,$user_id)){

      $sql = "INSERT INTO memos (user_id,category_id,heading,content) VALUES ({$user_id},:category_id,:heading,:content)";
  
      $this->prepare($sql);
      $this->bind(':category_id',$category_id,\PDO::PARAM_INT);
      $this->bind(':heading',$data['heading']);
      $this->bind(':content',$data['content']);
  
      return $this->execute();
    }

  }

  /**
   * ログインユーザが持つ特定のカテゴリーのすべてのメモを取得
   *
   * @param string $user_id
   * @param string $category_id
   * @return array
   */

  public function get($category_id,$user_id)
  {

    if($this->findCategory($category_id,$user_id)){

      $sql = "SELECT * FROM memos WHERE user_id = {$user_id} AND category_id = :category_id";
  
      $this->prepare($sql);
      $this->bind(':category_id',$category_id,\PDO::PARAM_INT);
  
      $this->execute();
  
      $data = $this->fetchAll(\PDO::FETCH_ASSOC);
  
      if($data === false){
        $this->handler->output("しばらくしてからもう一度お試しください");
      }
      
      return $data;
      
    }

  }

  /**
   * 特定のメモの取得
   *
   * @param string $memo_id
   * @param string $user_id
   * @return array
   */

  public function find($memo_id,$user_id)
  {
    $sql = "SELECT * FROM memos WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(":id",$memo_id,\PDO::PARAM_INT);

    $this->execute();

    $data = $this->fetch(\PDO::FETCH_ASSOC);

    if($data === false){
      $this->handler->output("不正なリクエスト");
    }
    
    return $data;
    
  }

  /**
   * 特定のメモの更新
   * 
   * @param string $memo_id
   * @param string $user_id
   * @param array $data
   * @return boolean
   */

  public function update($memo_id,$user_id,$data)
  {
    $sql = "UPDATE memos SET heading = :heading,content = :content,updated_at = :updated_at WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':heading',$data['heading']);
    $this->bind(':content',$data['content']);
    $this->bind(':updated_at',$this->today);
    $this->bind(':id',$memo_id,\PDO::PARAM_INT);

    return $this->execute();
  }

  /**
   * 特定のメモの削除
   *
   * @param string $memo_id
   * @param string $user_id
   * @return boolean
   */

  public function delete($memo_id,$user_id)
  {
    $sql = "DELETE FROM memos WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':id',$memo_id);

    return $this->execute();
    
  }

}