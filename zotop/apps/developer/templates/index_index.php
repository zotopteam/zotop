{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('developer/project/add')}">
				<i class="fa fa-plus"></i><b>{t('新建应用')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		
		<div class="container-fluid">
		{loop $projects $r}
		<div class="media rounded">
			<div class="media-left">
				<img src="{ZOTOP_URL_APPS}/{$r['dir']}/app.png">
			</div>
			<div class="media-body">
				<h4 class="media-heading">{$r['name']} <span>( {$r['id']} )</span> <span class="text-muted">{$r['version']}</span></h4>
				<p>{$r['description']}</p>
				<div class="manage">
							<a class="" href="{u('developer/project/index','project='.$r['dir'])}">
								<i class="fa fa-edit"></i> <b>{t('开发模式')}</b>
							</a>
							
							{if A($r['id'])}
							<span class="text-success pull-right">
								<i class="fa fa-check-circle"></i> <b>{t('已安装')}</b>
							</span>
							{else}
							<a class="js-confirm pull-right" href="{U('developer/project/delete',array('dir'=>$r['dir']))}">
								<i class="fa fa-times"></i> <b>{t('删除应用')}</b>
							</a>
							{/if}					
				</div>
			</div>
		</div>
		{else}
		<div class="nodata">{t('没有找到任何在建应用')}</div>
		{/loop}
		</div>
	</div>
	<div class="main-footer">
		<div class="footer-text">{A('developer.description')}</div>
	</div>
</div>
<style type="text/css">
.media{border:solid 1px #ebebeb;margin:20px 0!important;background:#f7f7f7;padding:20px;}
.media:hover{border:solid 1px #d5d5d5;}
.media-left img{width:80px;margin-right:20px;}
</style>
{template 'footer.php'}