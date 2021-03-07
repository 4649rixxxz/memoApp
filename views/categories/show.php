@extends(main)
<form action="<?php getUrlRoot("category/{$data['id']}/update");?>" method="post">
  {{ csrf_token }}
  <input type="text" name="cat_name" value="<?php echo $data['name'];?>">
  <?php getFirstErrMessage('cat_name'); ?>
  <button type="submit">更新</button>
</form>