<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class UserController extends Controller
{
 
  
  /**
   * ログインユーザのアカウント情報の表示
   *
   * @param object $request
   * @return string
   */

  public function show($request)
  {
    $user = $request->auth();
    
    return $this->view('users/show',[
      'user' => $user
    ]);
  }
    
  /**
   * ログインユーザのアカウント情報の更新画面の表示
   *
   * @param object $request
   * @return string
   */

  public function edit($request)
  {
    $user = $request->auth();
    
    return $this->view('users/edit',[
      'user' => $user
    ]);
  }


  /**
   * ログインユーザのアカウント情報の更新処理
   *
   * @param object $request
   */

  public function update($request)
  {
    $user = $request->auth();
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
        $request->session->setFlashMessage('success','ユーザ情報の更新が完了しました。再度ログインしてください。');
        //ログアウト
        unset($_SESSION['user_id']);
        //ログイン画面へリダイレクト
        redirect('login');
      }
    }
  }


  /**
   * ログインユーザのアカウント削除処理
   *
   * @param object $request
   */
  
  public function delete($request)
  {
    $user = $request->auth();

    if($this->model->delete($user->id)){
      //フラッシュメッセージの追加
      $request->session->setFlashMessage('end','ご利用ありがとうございました');
      //リダイレクト
      redirect();
    }
  }

}