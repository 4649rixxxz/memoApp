@extends(main)
<h1>アカウント情報</h1>
<?php if(isset($user->name)): ?>
<div>
  <?php echo $user->name;?>
</div>
<?php endif; ?>
<div>
  <?php echo $user->email?>
</div>
<div>
  <a href="<?php echo getUrlRoot('user/edit');?>">編集する</a>
</div>
<form action="<?php echo getUrlRoot("user/delete");?>" method="post">
  {{ csrf_token }}
  <button type="submit">アカウントを削除する</button>
</form>