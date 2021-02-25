@extends(auth)
<h1>Create an Account</h1>
<form action="<?php getUrlRoot('store');?>" method="post">
  {{ csrf_token }}
  <?php print_r($_SESSION);?>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input name="email" value="<?php getOldValue('email');?>" type="email" class="form-control" id="email" aria-describedby="emailHelp">
    <?php getFirstErrMessage('email');?>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input name="password" value="<?php getOldValue('password');?>" type="password" class="form-control" id="password">
    <?php getFirstErrMessage('password');?>
  </div>
  <div class="mb-3">
    <label for="confirmPassword" class="form-label">ConfirmPassword</label>
    <input name="confirmPassword" value="<?php getOldValue('confirmPassword');?>" type="password" class="form-control" id="confirmPassword">
    <?php getFirstErrMessage('confirmPassword');?>
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>