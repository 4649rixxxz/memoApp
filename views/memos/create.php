@extends(main)
<h1>メモの作成</h1>
<form action="<?php getUrlRoot("memo/category/{$id}/store");?>" method="post">
  {{ csrf_token }}
  <h3>メモの見出し</h3>
  <input type="text" name="heading" value="<?php getOldValue('heading');?>">
  <?php getFirstErrMessage('heading');?>
  <h3>内容</h3>
  <textarea name="content" maxlength="200" cols="40" rows="5" placeholder="メモを入力してください"><?php getOldValue('content');?></textarea>
  <?php getFirstErrMessage('content');?>
  <button type="submit">作成</button>
</form>