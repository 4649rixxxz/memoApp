<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;
use app\core\Session;

class UserController extends Controller
{
 
  public function index()
  {
    return $this->view('users/home');
  }

  
  //ユーザ情報の表示
  public function show($request)
  {
    $user = $request->user;
    
    return $this->view('users/show',[
      'user' => $user
    ]);
  }
    
  //ユーザ情報の編集画面
  public function edit($request)
  {
    $user = $request->user;
    
    return $this->view('users/edit',[
      'user' => $user
    ]);
  }


  //ユーザ情報の更新
  public function update($request)
  {
    $user = $request->user;
    $data = $request->postData;

    //バリデーションルール
    $rules = [
      'email' => [
        'required',
        'email',
        ['update_unique' => ['users:email',$user->email,$data['email']]]
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
      //エラー処理
      //エラーメッセージの取得
      $_SESSION['errorMessages'] = $validation->getErrorMessages();
      //リダイレクト
      redirect('user/edit');

    }else{
      //パスワードのハッシュ化
      $data['password'] = $this->createHashedPassword($data['password']);
      //DB処理
      if($user->update($user->id,$data)){
        //フラッシュメッセージの追加
        Session::setFlashMessage('success','ユーザ情報の更新が完了しました。');
        //ログイン画面へリダイレクト
        redirect('user/home');
      }else{
        die('しばらく時間を空けてから再度お試しください');
      }

    }
  }

}