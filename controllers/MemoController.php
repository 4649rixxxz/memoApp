<?php 

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;




class MemoController extends Controller
{
  public function index($request)
  {
    $id = $request->id;
    //ログインユーザの取得
    $user = $request->user;
    $memos = $this->model->getAll($user->id,$id);

    return $this->view('memos/index',[
      'id' => $id,
      'memos' => $memos
    ]);
  }

  public function create($request)
  {
    $id = $request->id;
    return $this->view('memos/create',[
      'id' => $id
    ]);
  }

  public function store($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->user;
    //カテゴリーのid
    $cat_id = $request->id;

    //バリデーションルール
    $rules = [
      'heading' => [
        'required',
        'max:20'
      ],
      'content' => [
        'required',
        'max:200',
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
      redirect("memo/category/{$cat_id}/create");

    }else{
      //バリデーション成功時
      if($this->model->insert($user->id,$cat_id,$data)){
        //リダイレクト
        redirect("memo/category/{$cat_id}/index");
      }else{
        die('しばらくしてから再度やり直してください');
      }
    }
  }

  public function show($request)
  {
    $user = $request->user;
    $memo_id = $request->id;

    $memo = $this->model->findOne($memo_id,$user->id);

    return $this->view('memos/show',[
      'memo' => $memo
    ]);
  }

  public function update($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->user;
    //カテゴリーのid
    $memo_id = $request->id;

    //バリデーションルール
    $rules = [
      'heading' => [
        'required',
        'max:20'
      ],
      'content' => [
        'required',
        'max:200',
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
      redirect("memo/{$memo_id}/show");

    }else{
      //バリデーション成功時
      if($this->model->update($memo_id,$user->id,$data)){
        //メモのカテゴリーを取得
        $category_id = $this->model->getCategory($memo_id,$user->id);
        if($category_id !== false){
          //リダイレクト
          redirect("memo/category/{$category_id}/index");
        }
      }else{
        die('しばらくしてから再度やり直してください');
      }
    }
  }

  public function delete($request)
  {
    $user = $request->user;
    $memo_id = $request->id;
    //メモのカテゴリーを取得
    $category_id = $this->model->getCategory($memo_id,$user->id);

    if($category_id !== false){
      if($this->model->delete($memo_id,$user->id)){
         //リダイレクト
         redirect("memo/category/{$category_id}/index");
       }else{
        die('しばらくしてから再度やり直してください');
       }
    }else{
      die('しばらくしてから再度やり直してください');
    }
  }


}