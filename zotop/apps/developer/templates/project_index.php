{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('developer')}">{t('开发助手')}</a>
			<s class="arrow">></s>
			<a href="{u('developer/project')}">{$app['name']}</a>
			<s class="arrow">></s>			
			{$title}
		</div>		
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/edit')}" data-width="800" data-height="400">
				<i class="icon icon-edit"></i>
				<b>{t('编辑信息')}</b>
			</a>						
		</div>
	</div>
	<div class="main-body scrollable">

		<img src="{ZOTOP_URL_APPS}/{$app['dir']}/app.png" class="none">

		<table class="table list">
			{loop $app $key $val}
			<tr>
				<td class="w300">{$attrs[$key]}({$key})</td>
				<td>{$val}</td>
			</tr>
			{/loop}
		</table>

	</div>
	<div class="main-footer">
		<div class="tips">{t('应用的基本信息')}</div>
	</div>
</div>

{template 'footer.php'}