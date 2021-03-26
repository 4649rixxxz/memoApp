<?php

namespace app\core;

use app\core\Model;

class Validation
{
  private $model;
  private $data;
  private $rules;
  private $lists = ATTR_JA;
  private $errorMessages = [];

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
          //Emailかどうか
          if($rule === 'email'){
            //ルール
            $reg_str = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
            //正規かどうか
            if(!preg_match($reg_str,$data[$key])){
             $this->setEmailMessage($key);
            }
          }
          //対象の値と指定した値が一致しているか
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
            //テーブル名とカラム名の取得
            $info = explode(":",$rule['unique'][0]);
            //テーブル名
            $table = $info[0];
            //カラム名
            $column = $info[1];
            //ユニークかどうか
            if($this->model->isUniqueValue($table,$column,$data[$key]) === false){
              //メッセージの格納
              $this->setUniqueMessage($key);
            }
          }
          //ユーザが既に存在するか
          if($key === 'email' && is_array($rule) && array_key_exists('exists',$rule)){
            //モデルの取得
            $authModel = $rule['exists'];
            if(!empty($data['email']) && !empty($data['password'])){
              //メールアドレスがレコードに存在するか
              $user = $authModel->findUser($data['email']);
              if($user !== false){
                //パスワードの認証
                if(!password_verify($data['password'],$user['password'])){
                  $this->setUnmatchedMessage('password');
                }
              }else{
                $this->setUnmatchedMessage('email');
              }
            }
          }
          //dbに存在する唯一の値を更新
          if($key === 'email' && is_array($rule) && array_key_exists('update_unique',$rule)){
             //[0]テーブル名と[1]カラム名を格納
             $info = explode(':',$rule['update_unique'][0]);
             //現在のメールアドレス
             $currentEmail = $rule['update_unique'][1];
             //更新後のメールアドレス
             $updateEmail = $rule['update_unique'][2];

             if(!$this->model->isUpdateUniqueValue($info,$currentEmail,$updateEmail)){
               $this->setUniqueMessage('email');
             }
          }
          //最大値
          if(preg_match('/^max:[1-9][0-9]*/',$rule)){
            $rule = str_replace('max:','',$rule);
            //文字列から数値へ型変換
            $maxNumber = intval($rule);
            //文字数が最大文字数かどうか調べる
            if(mb_strlen($data[$key]) > $maxNumber){
              //オーバー文字
              $overNum = mb_strlen($data[$key]);
              //最大文字数との差
              $diff = $overNum - $maxNumber;
              //エラーメッセージ
              $this->setMaxOverMessage($key,$diff);
            }
          }
          //最小値
          if(preg_match('/^min:[1-9][0-9]*/',$rule)){
            $rule = str_replace('min:','',$rule);
            //文字列から数値へ型変換
            $minNumber = intval($rule);
            //文字数が最低文字数を満たしているかどうか調べる
            if(mb_strlen($data[$key]) < $minNumber){
              $this->setMinValueMessage($key,$minNumber);
            }
          }

        }
      }else{
        //意図していない値が送られてきた時
        die('不正なリクエスト');
      }
    }
  }

  //エラーメッセージの取得
  public function getErrorMessages()
  {
    return $this->errorMessages;
  }

  //エラーがあるかどうか
  public function isError()
  {
    $errors = $this->getErrorMessages();

    if(count($errors) > 0){
      return true;
    }else{
      Session::unsetValue($this->rules);
      return false;
    }
  }


  /*----------------------------------------------------------------

  // エラーの表示時のために、name属性を基に日本語に変換する関数

  ----------------------------------------------------------------*/

  private function transIntoJapanese($key)
  {
    if(array_key_exists($key,$this->lists)){
      $key = $this->lists[$key];
    }

    return $key;
  }
  

  /*----------------------------------------------------------------

  // 以下、エラーメッセージ格納関数

  ----------------------------------------------------------------*/
  
  private function setRequiredMessage($key)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = $value."は必須項目です。";
  }

  private function setEmailMessage($key)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = $value."が不正なフォーマットのメールアドレスです。";
  }


  private function setMatchMessage($key,$targetKey)
  {
    $value = $this->transIntoJapanese($key);
    $value2 = $this->transIntoJapanese($targetKey);
    $this->errorMessages[$key][] = $value."は".$value2."と同じ値でなければなりません。";
  }



  private function setUniqueMessage($key)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = "この".$value."はすでに使用されています。";
  }

  private function setUnmatchedMessage($key)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = $value."が間違っています。";
  }

  private function setMaxOverMessage($key,$diff)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = $value."が最大文字数を".$diff."文字超えています。";
  }

  private function setMinValueMessage($key,$minNumber)
  {
    $value = $this->transIntoJapanese($key);
    $this->errorMessages[$key][] = $value."が最低文字数、".$minNumber."文字を満たしていません。";
  }


}