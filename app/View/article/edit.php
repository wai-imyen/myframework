
<div class="container">
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<form action="<?=HOST_URL . 'article/save/' . (isset($data['id'])? $data['id'] : '')?>" method='post'>
				<input type="hidden" name="_token" value="">
				<input type="hidden" name="mode" value="<?=$mode?>">
				<label for="title">標題：</label>
				<input type="text" name="title" class="form-control" value="<?=(isset($data['title']) ? $data['title'] : '')?>"><br>
				<label for="content">內容：</label>
				<textarea name="content" id="" cols="25" rows="16" class="form-control" ><?=(isset($data['content']) ? $data['content'] : '')?></textarea>
				<br>
				<input type="submit" class="btn btn-primary pull-right" id="submit" value='送出'>
			</form>
		</div>
	</div>
</div>
