@extends(main)
<?php getFlashMessage('success');?>
<h1>ユーザのホーム画面</h1>
<a href="<?php getUrlRoot('category/create');?>">追加</a>

<?php if(count($categories) > 0): ?>
  <?php foreach($categories as $category): ?>
    <div>
      <?php echo $category['name'];?>
      <?php echo $category['created_at'];?>
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
<?php endif; ?>