{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>	
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('developer/project/edit')}">
				<i class="fa fa-edit"></i>
				<b>{t('编辑信息')}</b>
			</a>						
		</div>
	</div>
	<div class="main-body scrollable">

		<img src="{ZOTOP_URL_APPS}/{$app['dir']}/app.png" class="hidden">

		<table class="table list">
			{loop $app $key $val}
			<tr>
				<td width="200"><b>{$attrs[$key]}</b> <span class="text-muted">{$key}</span></td>
				<td>{$val}</td>
			</tr>
			{/loop}
		</table>

	</div>
	<div class="main-footer">
		<div class="footer-text">{t('应用的基本信息')}</div>
	</div>
</div>

{template 'footer.php'}