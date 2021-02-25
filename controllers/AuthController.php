<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;
use app\models\Auth;
use app\core\Session;

class AuthController extends Controller
{

  public $model;

  public function __construct()
  {
    $this->model = new Auth;
  }

  //ログイン画面の表示
  public function index()
  {
    return $this->view('auth/login');
  }

  //新規登録画面の表示
  public function register()
  { 
    return $this->view('auth/register'); 
  }


  //ユーザーの新規登録処理
  public function store($request)
  {
    $data = $request->postData;

    //バリデーションルール
    $rules = [
      'email' => ['required','email',
      ['unique' => [$this->model::TABLE,'email']]
    ],
      'password' => ['required'],
      'confirmPassword' => [
        'required',
        ['match' => 'password']
      ]
    ];
    
    //インスタンス化
    $validation = new Validation($data,$rules);

    //バリデーション
    $validation->validate();

    //エラーがあるかどうか
    if($validation->isError()){
      //ハッシュ化するパスワード
      $keyLists = [
        'password',
        'confirmPassword'
      ];
      //パスワードのハッシュ化
      $data = $this->createHashedPassword($data,$keyLists);
      //DB処理
      if($this->model->insert($data)){
        //フラッシュメッセージの追加
        Session::setFlashMessage('success','新規登録が完了しました。以下の項目を入力してログインしてください。');
        //完了画面へリダイレクト
        redirect('login');
      }else{
        die('しばらく時間を空けてから再度お試しください');
      }
    }else{
      //エラー処理
      //エラーメッセージの取得
      $_SESSION['errorMessages'] = $validation->getErrorMessages();
      //リダイレクト
      redirect('register');
    }
  }

  
}