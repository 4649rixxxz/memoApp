@extends(auth)
<h1>Create an Account</h1>
<form action="" method="post">
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input name="email" value="" type="email" class="form-control" id="email" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input name="password" value="" type="password" class="form-control" id="password">
  </div>
  <div class="mb-3">
    <label for="confirmPassword" class="form-label">ConfirmPassword</label>
    <input name="confirmPassword" value="" type="password" class="form-control" id="confirmPassword">
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>