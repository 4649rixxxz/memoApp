@extends(main)

<div>
  <a href="<?php echo getUrlRoot("memo/category/{$id}/create");?>">追加</a>
</div>

<?php if(count($memos) > 0): ?>
  <?php foreach($memos as $memo):?>
  <div class="mt-4">
    <h1><?php echo $category_name;?></h1>
    <h3><?php echo $memo['heading'];?></h3>
    <div><?php echo $memo['content'];?></div>
    <small><?php echo $memo['updated_at']; ?></small>
    <a href="<?php echo getUrlRoot("memo/{$memo['id']}/show");?>">表示</a>
    <form action="<?php echo getUrlRoot("memo/{$memo['id']}/delete");?>" method="post">
      {{ csrf_token }}
      <button type="submit">削除</button>
    </form>
  </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="mt-4">
    <h1><?php echo $category_name;?></h1>
    <h2>メモがメモがまだありません</h2>
  </div>
<?php endif; ?>