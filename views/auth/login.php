@extends(auth)
<h1>ログイン画面</h1>
<?php getFlashMessage('success');?>
<form action="" method="post">
  {{ csrf_token }}
  <div class="mb-3">
    <label for="email" class="form-label">メールアドレス</label>
    <input name="email" value="<?php getOldValue('email');?>" type="email" class="form-control" id="email" aria-describedby="emailHelp">
    <?php getFirstErrMessage('email');?>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">パスワード</label>
    <input name="password" value="<?php getOldValue('password');?>" type="password" class="form-control" id="password">
    <?php getFirstErrMessage('password');?>
  </div>
  <button type="submit" class="btn btn-primary">ログイン</button>
</form>