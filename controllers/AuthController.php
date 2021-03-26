<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class AuthController extends Controller
{

  /**
   * ログイン画面の表示
   *
   * @return string
   */

  public function index()
  {
    return $this->view('auth/login');
  }

  /**
   * アカウント登録画面の表示
   *
   * @return string
   */

  public function register()
  { 
    return $this->view('auth/register'); 
  }


  /**
   * ユーザの新規登録
   *
   * @param object $request
   */

  public function store($request)
  {
    $data = $request->postData;

    //バリデーションルール
    $rules = [
      'email' => [
        'required',
        'email',
        ['unique' => ['users:email']]
      ],
      'password' => ['required','min:8'],
      'confirmPassword' => [
        'required',
        'min:8',
        ['match' => 'password']
      ]
    ];
    
    //インスタンス化
    $validation = new Validation($data,$rules);

    //バリデーション
    $validation->validate();

    //エラーがあるかどうか
    if($validation->isError()){
      //エラー処理
      //エラーメッセージの取得
      $_SESSION['errorMessages'] = $validation->getErrorMessages();
      //リダイレクト
      redirect('register');

    }else{
      //パスワードのハッシュ化
      $data['password'] = $this->createHashedPassword($data['password']);
      //DB処理
      if($this->model->insert($data)){
        //フラッシュメッセージの追加
        $request->session->setFlashMessage('success','新規登録が完了しました。ログインしてください。');
        //完了画面へリダイレクト
        redirect('login');
      }
      
    }
  }

  /**
   * ログイン処理
   *
   * @param object $request
   */

  public function login($request)
  {

    $data = $request->postData;
    
    //バリデーションルール
    $rules = [
      'email' => [
        'required',
        'email',
        ['exists' => $this->model]
      ],
      'password' => ['required'],
    ];
    
    //インスタンス化
    $validation = new Validation($data,$rules);

    //バリデーション
    $validation->validate();

    if($validation->isError()){
       //エラー処理
      //エラーメッセージの取得
      $_SESSION['errorMessages'] = $validation->getErrorMessages();
      //リダイレクト
      redirect('login');

    }else{
      //ユーザの取得
      $user = $this->model->findUser($data['email']);
      //ログイン成功
      $request->session->setLoginUser($user['id']);
      //ユーザのホームページにリダイレクト
      redirect('home');
    }
  }

  /**
   * ログアウト処理
   *
   * @param object $request
   */
  
  public function logout($request)
  {
    if($request->postData['logout'] === 'logout'){
      //ログアウトメッセージの表示
      $request->session->setFlashMessage('logout','ログアウトしました。');
      //リダイレクト
      redirect();
    }
  }

  
}