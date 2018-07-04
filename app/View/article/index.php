<div class="row">
	<div class="col-md-3">
		<div class="" style="margin-left: 50px;">
			<h4>最近的文章</h4><br>
			<div style="line-height: 30px">
				<li><a href="">20180301 - 最近的文章</a></li>
				<li><a href="">20180301 - 最近的文章</a></li>
				<li><a href="">20180301 - 最近的文章</a></li>
				<li><a href="">20180301 - 最近的文章</a></li>
				<li><a href="">20180301 - 最近的文章</a></li>
			</div>
			
		</div>
		
	</div>
	<div class="col-md-8 col-xs-12  articleIndex">
		<a href="article/create" role='btn' class='btn btn-primary btn-sm pull-right' >新增文章</a><br>
		<? foreach($datas as $key => $val):?>
		<h5 class=" "><?=$val['created_at']?></h5>
		<div class="panel panel-default">
			<div class="panel-heading" ><?=$val['title']?></div>
			<div class="panel-body"><?=nl2br(substr($val['content'], 0, 200))?> ...
				<br>
				<a href="article/show/<?=$val['id']?>" id="read" class="pull-right">繼續閱讀...</a>
			</div>
		</div>
		<br>
		<? endforeach;?>
	</div>
</div>