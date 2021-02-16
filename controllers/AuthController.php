<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class AuthController extends Controller
{

  public $model;

  public function login()
  {
    return $this->view('auth/login');
  }

  public function register($request)
  {
    if($request->isGet()){
      return $this->view('auth/register');
    }

    if($request->isPost())
    {
      $data = $request->postData;

      //バリデーションルール
      $rules = [
        'email' => ['required','email'],
        'password' => ['required'],
        'confirmPassword' => [
          'required',
          ['match' => 'password']
        ]
      ];
      
      //バリデーション
      $validation = new Validation($data,$rules);
      //日本語化
      $validation->lists = [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'confirmPassword' => '確認用パスワード'
      ];

      $validation->validate();

      //エラーがあるかどうか
      if($validation->isError()){
        //DB処理
      }else{
        //エラー処理
      }

    }
  }
}