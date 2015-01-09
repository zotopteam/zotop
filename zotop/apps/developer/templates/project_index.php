{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>	
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/edit')}" data-width="800" data-height="400">
				<i class="icon icon-edit"></i>
				<b>{t('编辑信息')}</b>
			</a>						
		</div>
	</div>
	<div class="main-body scrollable">

		<img src="{ZOTOP_URL_APPS}/{$data['dir']}/app.png" class="none">

		<table class="table list">
			{loop $data $key $val}
			<tr>
				<td class="w300">{$attrs[$key]}({$key})</td>
				<td>{$val}</td>
			</tr>
			{/loop}
		</table>

	</div>
	<div class="main-footer">
		<div class="tips">{t('管理 apps 目录下在建的应用')}</div>
	</div>
</div>

{template 'footer.php'}