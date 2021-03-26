<?php

namespace app\core;

use app\exception\Handler;

class Model
{
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  private $dbh;
  private $stmt;

  protected $handler;
  protected $today;

  public function __construct()
  {
    $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname.'; charset=utf8';
    $options = array(
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    );

    //エラーインスタンスの生成
    $this->handler = new Handler;
    //更新日に今日の日付を格納
    $this->today = date("Y-m-d");
    
    try{

      $this->dbh = new \PDO($dsn,$this->user,$this->pass,$options);
      
    }catch(\PDOException $e){
      $this->handler->output($e->getMessage());
    }

  }
  
  /**
   * prepareメソッドの実行
   *
   * @param string $sql
   */

  public function prepare($sql)
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  /**
   * bindValueメソッドの実行
   *
   * @param string $param
   * @param string|int $value
   * @param string $type
   */

  public function bind($param,$value,$type = null)
  {
    if(is_null($type)){
      switch(true){
        case is_int($value):
          $type = \PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = \PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = \PDO::PARAM_NULL;
          break;
        default:
          $type = \PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param,$value,$type);
  }

  /**
   * executeメソッドの実行
   *
   * @return boolean
   */

  public function execute()
  {
    if($this->stmt->execute()){
      return true;
    }else{
      return false;
    }

  }

  /**
   * queryメソッドの実行
   *
   * @return boolean
   */

  public function query($sql)
  {
    $this->stmt = $this->dbh->query($sql);

    if($this->stmt !== false){
      return true;
    }else{
      $this->handler->output("しばらくしてからもう一度お試しください");
    }

  }

  /**
   * fetchメソッドの実行
   *
   * @return array
   */

  public function fetch($mode = "")
  {
    $result = $this->stmt->fetch($mode);

    if($result !== false){
      return $result;
    }else{
      $this->handler->output("不正なリクエスト");
    }
  }

  /**
   * fetchAllメソッドの実行
   *
   * @return array
   */

  public function fetchAll()
  {
    $result = $this->stmt->fetchAll();
    
    if($result !== false){
      return $result;
    }else{
      $this->handler->output("しばらくしてからもう一度お試しください");
    }
  }

  /**
   * テーブル内の唯一のレコードを抽出する
   *
   * @param string $table
   * @param string $column
   * @param string $value
   * @return boolean
   */

  public function isUniqueValue($table,$column,$value)
  {
    $sql = "SELECT * FROM {$table} WHERE {$column} = :value";
    
    $this->prepare($sql);
    $this->bind(':value',$value);

    if($this->execute()){

      $result = $this->fetch();

      if($result !== false){
        //レコードがある場合
        return false;
      }else{
        //レコードがない場合
        return true;
      }
    }
  }

  /**
   * Undocumented function
   *
   * @param array $info
   * @param string $currentValue
   * @param string $updateValue
   * @return boolean
   */

  public function isUpdateUniqueValue($info,$currentValue,$updateValue)
  {
    //現在の値と更新後の値が同じ場合
    if($currentValue === $updateValue){
      return true;
    }

    //現在の値と更新後の値が異なる場合
    if(!empty($updateValue) && $currentValue !== $updateValue){
      //更新後の値は唯一の値であるかどうか
      return $this->isUniqueValue($info[0],$info[1],$updateValue);
    }

    return false;
    
  }

  
}