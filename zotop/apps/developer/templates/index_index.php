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
		{if empty($projects)}
			<div class="nodata">{t('没有找到任何在建应用')}</div>
		{else}
				{loop $projects $r}
				<div class="projects">
					<div class="icon">
						<img src="{ZOTOP_URL_APPS}/{$r['dir']}/app.png">
					</div>
					<div class="info">
						<div class="title">{$r['name']} <span>( {$r['id']} )</span></div>

						<table class="attr">
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
							<tr>
								<td class="w100">{t('描述')}</td>
								<td colspan="5">{$r['description']}</td>
							</tr>
						</table>
						<div class="manage">
							<a class="btn btn-icon-text btn-highlight" href="{u('developer/project/index','dir='.$r['dir'])}">
								<i class="icon icon-edit"></i><b>{t('管理应用')}</b>
							</a>

							<a class="btn btn-icon-text dialog-confirm hidden" href="{U('developer/project/delete')}">
								<i class="icon icon-delete"></i><b>{t('删除应用')}</b>
							</a>								
						</div>
					</div>
				</div>
				{/loop}
		{/if}
	</div>
	<div class="main-footer">
		<div class="tips">{t('管理 apps 目录下在建的应用')}</div>
	</div>
</div>
<style type="text/css">
.projects{border:solid 2px #ebebeb;margin:20px 0;background:#F3F3F3;}
.projects:hover{border:solid 2px #d5d5d5;}
.projects div.icon {float:left;padding:20px 0;width:120px;text-align:center;overflow:hidden;}
.projects div.icon img{width:80px;}
.projects div.info {margin-left:121px;font-size:15px;line-height:30px;}
.projects div.title{line-height:50px;font-size:24px;}
.projects div.title span{font-size:20px;color:#666;}
.projects div.manage{padding:10px 0;}
.projects table{border-top:solid 1px #ebebeb;}
.projects table td{padding:5px 0;border-bottom:solid 1px #ebebeb;}
</style>
{template 'footer.php'}