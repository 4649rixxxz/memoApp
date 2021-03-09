@extends(main)
<?php getFlashMessage('success');?>
<h1><?php echo $user->email;?>様</h1>
<div class="mt-5 mb-5"> 
  <a href="<?php getUrlRoot('category/create');?>">追加</a>
</div>

<?php if(count($categories) > 0): ?>
  <?php foreach($categories as $category): ?>
    <div>
      <a href="<?php getUrlRoot("memo/category/{$category['id']}/index");?>"><?php echo $category['name'];?></a>
    </div>
    <div>
      <a href="<?php getUrlRoot("category/{$category['id']}/show");?>">更新</a>
    </div>
    <div>
      <form action="<?php getUrlRoot("category/{$category['id']}/delete");?>" method="post">
        {{ csrf_token }}
        <button type="submit">削除</button>
      </form>
    </div>
  <?php endforeach; ?>
<?php else:?>
  <h2>メモを追加しよう</h2>
<?php endif; ?>