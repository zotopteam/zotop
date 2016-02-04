{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/add')}" data-width="800" data-height="400">
				<i class="icon icon-add"></i><b>{t('新建应用')}</b>
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
				<h4 class="media-heading">{$r['name']} <span>( {$r['id']} )</span></h4>
				<p>{$r['description']}</p>
				<table class="table attr">
					<tr>
						<td class="w100">{t('标识')}</td>
						<td>{$r['id']}</td>
						<td class="w100">{t('目录')}</td>
						<td>{$r['dir']}</td>
						<td class="w100">{t('版本')}</td>
						<td>{$r['version']}</td>
					</tr>
					<tr>
						<td class="w100">{t('类型')}</td>
						<td>{$r['type']}</td>

						<td class="w100">{t('依赖')}</td>
						<td>{$r['dependencies']}</td>
						<td class="w100">{t('数据表')}</td>
						<td>{$r['tables']}</td>
					</tr>
					<tr>
						<td class="w100">{t('作者')}</td>
						<td>{$r['author']}</td>
						<td class="w100">{t('邮箱')}</td>
						<td>{$r['email']}</td>
						<td class="w100">{t('网站')}</td>
						<td colspan="3">{$r['homepage']}</td>
					</tr>
				</table>
			</div>
		</div>
		{else}
		<div class="nodata">{t('没有找到任何在建应用')}</div>
		{/loop}
		</div>
	</div>
	<div class="main-footer">
		<div class="tips">{t('管理 apps 目录下在建的应用')}</div>
	</div>
</div>
<style type="text/css">
.media{border:solid 1px #ebebeb;margin:20px 0!important;background:#f7f7f7;padding:20px;}
.media:hover{border:solid 1px #d5d5d5;}
.media-left img{width:80px;}
.media div.info {margin-left:121px;font-size:15px;line-height:30px;}
.media div.title{line-height:50px;font-size:24px;}
.media div.title span{font-size:20px;color:#666;}
.media div.manage{padding:10px 0;}
.media table{border-top:solid 1px #ebebeb;}
.media table td{padding:5px 0;border-bottom:solid 1px #ebebeb;}
</style>
{template 'footer.php'}