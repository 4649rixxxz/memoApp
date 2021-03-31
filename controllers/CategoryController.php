<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class CategoryController extends Controller
{
  /**
   * ユーザーが持つすべてのカテゴリーを表示する
   *
   * @param object $request
   * @return string
   */

  public function index($request)
  {
    $user = $request->auth();
    $categories = $this->model->get($user->id);
      
    return $this->view('users/home',[
      'user' => $user,
      'categories' => $categories
    ]);
  }


  /**
   * 新規カテゴリーの登録画面の表示
   *
   * @return string
   */

  public function create()
  {
    return $this->view('categories/create');
  }

  /**
   * 新規カテゴリーの登録
   *
   * @param object $request
   */

  public function store($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->auth();

    //バリデーションルール
    $rules = [
      'cat_name' => [
        'required',
        'max:10',
     ],
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
      redirect('category/create');

    }else{
      //バリデーション成功時
      if($this->model->insert($user->id,$data)){
        //リダイレクト
        redirect('home');
      }
    }
  }

  /**
   * 特定のカテゴリーの編集画面の表示
   *
   * @param object $request
   * @return string
   */

  public function show($request)
  {
    $user = $request->auth();
    $param = $request->id;


    $data = $this->model->find($user->id,$param);

    return $this->view('categories/show',[
      'data' => $data
    ]);
  }

  /**
   * 特定のカテゴリーの更新
   *
   * @param object $request
   */

  public function update($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->auth();

    //バリデーションルール
    $rules = [
      'cat_name' => [
        'required',
        'max:10',
     ],
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
      redirect("category/{$request->id}/show");

    }else{
      //バリデーション成功時
      if($this->model->update($user->id,$request->id,$data)){
        //リダイレクト
        redirect('home');
      }
    }
  }

  /**
   * 特定のカテゴリーの削除
   *
   * @param object $request
   */

  public function delete($request)
  {
    //ログインユーザの取得
    $user = $request->auth();
    
    if($this->model->delete($user->id,$request->id)){
      //リダイレクト
      redirect('home');
    }
  }



}