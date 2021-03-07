<?php

namespace app\core;


class Model
{
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  private $dbh;
  private $stmt;

  public function __construct()
  {
    //set dsn
    $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname.'; charset=utf8';
    $options = array(
      \PDO::ATTR_PERSISTENT => true,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    );

    //create \PDO instance
    try{

      $this->dbh = new \PDO($dsn,$this->user,$this->pass,$options);
    }catch(\PDOException $e){
      die('しばらくしてからもう一度お試しください');
    }

  }
  //Prepare statement with query
  public function prepare($sql)
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  // Binding param
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


  public function execute()
  {
    return $this->stmt->execute();
  }

  public function query($sql)
  {
    $this->stmt = $this->dbh->query($sql);
  }

  public function fetch($mode = \PDO::FETCH_BOTH)
  {
    $result = $this->stmt->fetch($mode);

    return $result;
  }

  public function fetchAll($mode = \PDO::FETCH_BOTH)
  {
    $result = $this->stmt->fetchAll($mode);

    return $result;
  }


  public function isUniqueValue($table,$column,$value)
  {
    $sql = "SELECT * FROM {$table} WHERE {$column} = :value";
    
    $this->prepare($sql);
    $this->bind(':value',$value);

    if($this->execute()){

      $result = $this->fetch(\PDO::FETCH_ASSOC);

      if($result !== false){
        //レコードがある場合
        return false;
      }else{
        //レコードがない場合
        return true;
      }
    }else{
      return false;
    }

   
  }


  public function isUpdateUniqueValue($info,$currentValue,$updateValue)
  {
    //現在の値と更新後の値が同じ場合
    if($currentValue === $updateValue){
      return true;
    }

    if(!isset($updateValue) && $currentValue !== $updateValue){
      return $this->isUniqueValue($info[0],$info[1],$updateValue);
    }
    
  }

  
}