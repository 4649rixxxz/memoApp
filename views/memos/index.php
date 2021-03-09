@extends(main)

<div>
  <a href="<?php getUrlRoot("memo/category/{$id}/create");?>">追加</a>
</div>

<?php if(count($memos) > 0): ?>
  <?php foreach($memos as $memo):?>
  <div class="mt-4">
    <h3><?php echo $memo['heading'];?></h3>
    <div><?php echo $memo['content'];?></div>
    <a href="<?php getUrlRoot("memo/{$memo['id']}/show");?>">表示</a>
    <form action="<?php getUrlRoot("memo/{$memo['id']}/delete");?>" method="post">
      {{ csrf_token }}
      <button type="submit">削除</button>
    </form>
  </div>
  <?php endforeach; ?>
<?php else: ?>
  <h1>メモがまだありません</h1>
<?php endif; ?>