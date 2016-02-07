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
			<thead>
				<tr>
					<td>{t('名称')}</td>
					<td>{t('标识')}</td>
					<td>{t('值')}</td>
					<td>{t('调用')}</td>
				</tr>
			</thead>
			<tbody>
				{loop $app $key $val}
				<tr>
					<td width="200"><b>{$attrs[$key]}</b></td>
					<td>{$key}</td>				
					<td>{$val}</td>
					<td>A('{$app['id']}.{$key}')</td>
				</tr>
				{/loop}				
			</tbody>
		</table>

	</div>
	<div class="main-footer">
		<div class="footer-text">{t('应用的基本信息')}</div>
	</div>
</div>

{template 'footer.php'}