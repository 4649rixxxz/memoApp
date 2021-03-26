@extends(main)

<form action="<?php echo getUrlRoot("memo/{$memo['id']}/update");?>" method="post">
  {{ csrf_token }}
  <input type="text" name="heading" value="<?php echo $memo['heading'];?>">
  <?php getFirstErrMessage('heading'); ?>
  <textarea name="content" maxlength="200" cols="40" rows="5"><?php echo $memo['content'];?></textarea>
  <?php getFirstErrMessage('content'); ?>
  <button type="submit">更新</button>
</form>