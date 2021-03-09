<?php

namespace app\models;

use app\core\Model;

class Memo extends Model
{
  public function insert($user_id,$cat_id,$data)
  {
    $sql = "INSERT INTO memos (user_id,category_id,heading,content) VALUES ({$user_id},:category_id,:heading,:content)";

    $this->prepare($sql);
    $this->bind(':category_id',$cat_id);
    $this->bind(':heading',$data['heading']);
    $this->bind(':content',$data['content']);

    return $this->execute();
  }

  public function getAll($user_id,$cat_id)
  {
    $sql = "SELECT * FROM memos WHERE user_id = {$user_id} AND category_id = :category_id";

    $this->prepare($sql);
    $this->bind(':category_id',$cat_id);

    $this->execute();

    $data = $this->fetchAll(\PDO::FETCH_ASSOC);

    if($data !== false){
      return $data;
    }else{
      return [];
    }
  }

  public function findOne($memo_id,$user_id)
  {
    $sql = "SELECT * FROM memos WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(":id",$memo_id);

    $this->execute();

    $data = $this->fetch(\PDO::FETCH_ASSOC);

    if($data === false){
      die('不正なリクエスト');
    }
    
    return $data;
    
  }

  public function update($memo_id,$user_id,$data)
  {
    $sql = "UPDATE memos SET heading = :heading,content = :content WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':heading',$data['heading']);
    $this->bind(':content',$data['content']);
    $this->bind(':id',$memo_id);

    return $this->execute();
  }

  public function getCategory($memo_id,$user_id)
  {
    $sql = "SELECT category_id FROM memos WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':id',$memo_id);

    if($this->execute()){
      return $this->fetch(\PDO::FETCH_COLUMN);
    }else{
      die('しばらくしてからもう一度お試しください');
    }

  }

  public function delete($memo_id,$user_id)
  {
    $sql = "DELETE FROM memos WHERE id = :id AND user_id = {$user_id}";

    $this->prepare($sql);
    $this->bind(':id',$memo_id);

    return $this->execute();
  }

}