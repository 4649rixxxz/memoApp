<?php 

namespace app\controllers;

use app\core\Controller;
use app\core\Validation;
use app\models\Category;

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
    $category = new Category;
    $category_name = $category->getCategory($user->id,$category_id)["name"];
    //すべてのメモを取得
    $memos = $this->model->getAll($user->id,$category_id);

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
    $category_id = $request->category_id;
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
    $category_id = $request->category_id;

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

    $memo = $this->model->findOne($memo_id,$user->id);

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
      //メモのカテゴリーを取得
      $category_id = $this->model->getCategory($memo_id,$user->id);

      if($this->model->update($memo_id,$user->id,$data)){
      
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
    $memo_id = $request->id;

    //メモのカテゴリーを取得
    $category_id = $this->model->getCategory($memo_id,$user->id);
    
    if($this->model->delete($memo_id,$user->id)){
      redirect("memo/category/{$category_id}/index");
    }
      
  }


}