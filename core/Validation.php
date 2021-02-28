<?php

namespace app\core;

use app\core\Model;

class Validation
{
  public $model;
  public $data;
  public $rules;
  public $lists = ATTR_JA;
  public $errorMessages = [];

  public function __construct($data,$rules)
  {
    $this->data = $data;
    $this->rules = $rules;
    $this->model = new Model;
  }

  public function validate()
  {
    $data = $this->data;
    $rules = $this->rules;

    //ルールの確認
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
          //唯一の値か(unique)
          if(is_array($rule) && array_key_exists('unique',$rule)){
            //テーブルの名前
            $table = $rule['unique'][0];
            //カラム名
            $column = $rule['unique'][1];
            //ユニークかどうか
            if($this->model->isUniqueValue($table,$column,$data[$key]) === false){
              //メッセージの格納
              $this->setUniqueMessage($key);
            }
          }
          if(is_array($rule) && array_key_exists('exists',$rule)){
            //モデルの取得
            $authModel = $rule['exists'];
            if(!empty($data[$key]) && !empty($data['password'])){
              //メールアドレスがレコードに存在するか
              $user = $authModel->findUser($data[$key]);
              if($user !== false){
                //パスワードの認証
                if(!password_verify($data['password'],$user['password'])){
                  $this->setUnmatchedMessage('password');
                }
              }else{
                $this->setUnmatchedMessage($key);
              }
            }
          }
        }
      }
    }
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
  
  protected function setRequiredMessage($key)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."は必須項目です。";
  }

  protected function setEmailMessage($key)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."が不正なフォーマットのメールアドレスです。";
  }


  protected function setMatchMessage($key,$targetKey)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."は".$this->lists[$targetKey]."と同じ値でなければなりません。";
  }



  protected function setUniqueMessage($key)
  {
    $this->errorMessages[$key][] = "この".$this->lists[$key]."はすでに使用されています。";
  }

  protected function setUnmatchedMessage($key)
  {
    $this->errorMessages[$key][] = $this->lists[$key]."が間違っています。";
  }


}