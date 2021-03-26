@extends(main)
<h1>更新画面</h1>
<form action="<?php echo getUrlRoot('user/update');?>" method="post">
  {{ csrf_token }}
  <div class="mb-3">
    <label for="email" class="form-label">メールアドレス</label>
    <input name="email" value="<?php getOldValue('email',$user->email);?>" type="email" class="form-control" id="email" aria-describedby="emailHelp">
    <?php getFirstErrMessage('email');?>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">新しいパスワード(8文字以上)</label>
    <input name="password" value="<?php getOldValue('password');?>" type="password" class="form-control" id="password">
    <?php getFirstErrMessage('password');?>
  </div>
  <div class="mb-3">
    <label for="confirmPassword" class="form-label">確認用パスワード</label>
    <input name="confirmPassword" value="<?php getOldValue('confirmPassword');?>" type="password" class="form-control" id="confirmPassword">
    <?php getFirstErrMessage('confirmPassword');?>
  </div>
  <button type="submit" class="btn btn-primary">更新する</button>
</form>