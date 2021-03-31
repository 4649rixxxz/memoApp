<?php 

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;

class MemoController extends Controller
{
  /**
   * 特定のカテゴリーのすべてのメモを表示する
   *
   * @param object $request
   * @return string
   */

  public function index($request)
  {
    //カテゴリーidの取得
    $category_id = $request->category_id;
    //ログインユーザの取得
    $user = $request->auth();
    //カテゴリー名を取得
    $category_name = $this->model->findCategory($category_id,$user->id)["name"];
    //すべてのメモを取得
    $memos = $this->model->get($category_id,$user->id);

    return $this->view('memos/index',[
      'id' => $category_id,
      'category_name' => $category_name,
      'memos' => $memos
    ]);
  }

  /**
   * メモの新規登録画面の表示
   *
   * @param object $request
   * @return string
   */

  public function create($request)
  {
    $user = $request->auth();
    $category_id = $this->model->findCategory($request->category_id,$user->id)["id"];
    return $this->view('memos/create',[
      'id' => $category_id
    ]);
  }

  /**
   * メモの新規登録処理
   * 
   * @param object $request
   */

  public function store($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->auth();
    //カテゴリーのid
    $category_id = $this->model->findCategory($request->category_id,$user->id)["id"];

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
      //エラーメッセージの取得
      $_SESSION['errorMessages'] = $validation->getErrorMessages();
      //リダイレクト
      redirect("memo/category/{$category_id}/create");

    }else{
      //バリデーション成功時
      if($this->model->insert($user->id,$category_id,$data)){
        redirect("memo/category/{$category_id}/index");
      }
    }
  }


  /**
   * 特定のメモの編集画面の表示
   *
   * @param object $request
   * @return string
   */

  public function show($request)
  {
    $user = $request->auth();
    $memo_id = $request->id;

    $memo = $this->model->find($memo_id,$user->id);

    return $this->view('memos/show',[
      'memo' => $memo
    ]);
  }


  /**
   * 特定のメモの更新
   *
   * @param object $request
   */

  public function update($request)
  {
    $data = $request->postData;
    //ログインユーザの取得
    $user = $request->auth();
    //メモの取得
    $memo = $this->model->find($request->id,$user->id);

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
      redirect("memo/{$memo['id']}/show");

    }else{
      //メモのカテゴリーを取得
      $category_id = $this->model->findCategory($memo['category_id'],$user->id)["id"];

      if($this->model->update($memo['id'],$user->id,$data)){
      
        redirect("memo/category/{$category_id}/index");
      }
    }
  }


  /**
   * 特定のメモの削除
   *
   * @param object $request
   */

  public function delete($request)
  {
    $user = $request->auth();
    //メモの取得
    $memo = $this->model->find($request->id,$user->id);
    
    if($this->model->delete($memo['id'],$user->id)){
      //メモのカテゴリーを取得
      $category_id = $this->model->findCategory($memo['category_id'],$user->id)["id"];
      redirect("memo/category/{$category_id}/index");
    }
      
  }


}