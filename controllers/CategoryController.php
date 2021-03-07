<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class CategoryController extends Controller
{
  public function index($request)
  {
    $categories = $this->model->getCategories($request->user->id);

  
    return $this->view('users/home',[
      'categories' => $categories
    ]);
  }


  public function create()
  {
    return $this->view('categories/create');
  }

  public function store($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->user;

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
      // var_dump($user->id);
      // exit;
      //バリデーション成功時
      if($this->model->insert($user->id,$data)){
        //リダイレクト
        redirect('home');
      }else{
        die('しばらくしてから再度やり直してください');
      }
    }
  }

  public function show($request)
  {
    $user = $request->user;
    $param = $request->id;


    $data = $this->model->findOne($user->id,$param);

    return $this->view('categories/show',[
      'data' => $data
    ]);
  }

  public function update($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->user;

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
      if($this->model->updateName($user->id,$request->id,$data)){
        //リダイレクト
        redirect('home');
      }else{
        die('しばらくしてから再度やり直してください');
      }
    }
  }

  public function delete($request)
  {
    //ログインユーザの取得
    $user = $request->user;
    
    if($this->model->deleteName($user->id,$request->id)){
      //リダイレクト
      redirect('home');
    }else{
      die('しばらくしてから再度やり直してください');
    }
  }



}