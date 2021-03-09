@extends(main)
<h1>カテゴリー名を入力してください</h1>
<form action="<?php getUrlRoot('category/store');?>" method="post">
  {{ csrf_token }}
  <input type="text" name="cat_name" value="<?php getOldValue('cat_name') ?>">
  <?php getFirstErrMessage('cat_name');?>
  <button type="submit">追加</button>
</form>