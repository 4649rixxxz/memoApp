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
  <a href="<?php getUrlRoot('user/edit');?>">編集する</a>
</div>