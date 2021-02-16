<?php

namespace app\core;


class Validation
{
  public $data;
  public $rules;
  public $lists = [];
  public $errorMessages = [];

  public function __construct($data,$rules)
  {
    $this->data = $data;
    $this->rules = $rules;
  }

  public function validate()
  {
    $data = $this->data;
    $rules = $this->rules;

    foreach($rules as $key => $ruleArray)
    {
      if(array_key_exists($key,$data)){
        //それぞれのルールを取得
        foreach($ruleArray as $rule)
        {
          //必須項目
          if($rule === 'required'){
            if(empty($data[$key])){
              $this->setRequiredMessage($key);
            }
          }
          //Email
          if($rule === 'email'){
            //ルール
            $reg_str = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
            //正規かどうか
            if(!preg_match($reg_str,$data[$key])){
             $this->setEmailMessage($key);
            }
          }
          //Match
          if(is_array($rule) && array_key_exists('match',$rule)){
            //対象のキーを取得
            $targetKey = $rule['match'];
            //同じ値かどうか
            if($data[$key] !== $data[$targetKey]){
              $this->setMatchMessage($key,$targetKey);
            }
          }
        }
      }
    }
  }


  public function setRequiredMessage($key)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."は必須項目です";
  }

  public function setEmailMessage($key)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."が不正なEメールアドレスです";
  }


  public function setMatchMessage($key,$targetKey)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."は".$this->lists[$targetKey]."と同じ値でなければなりません";
  }


  public function getErrorMessages()
  {
    return $this->errorMessages;
  }

  public function isError()
  {
    $errors = $this->getErrorMessages();

    if(count($errors) == 0){
      return true;
    }else{
      return false;
    }
  }

}