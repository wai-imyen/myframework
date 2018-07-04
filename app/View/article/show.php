<div class="col-md-10 col-md-offset-1">
	<a href="<?=HOST_URL . 'article/edit/' . $data['id']?>" role="btn" class="btn btn-primary pull-right" id="show_btn">編輯</a>
	<form action="" method="post">
		<input type="hidden" name="_token" value="">
		<input type="hidden" name="_method" value="delete">
		<a href="javascript:if(confirm('確認刪除文章？')) {location.href='<?=HOST_URL . 'article/delete/' . $data['id']?>'}" role="btn" class="btn btn-danger pull-right">刪除</a>
	</form>
	<br>
	<h5 class="text-default "><?=$data['created_at']?></h5>
	<div class="panel panel-default">
		<div class="panel-heading title"><?=$data['title']?></div>
	  <div class="panel-body"><?=nl2br($data['content'])?></div>
	</div>
</div>