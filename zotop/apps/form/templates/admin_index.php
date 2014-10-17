{template 'header.php'}

<div class="side">
{template 'form/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('form/admin/add')}">
				<i class="icon icon-add"></i><b>{t('添加表单')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table list sortable">
			<thead>
				<tr>
								
					<td class="drag"></td>
					<td class="w40 center">{t('状态')}</td>
					<td class="w60 center none">{t('图标')}</td>
					<td class="w400">{t('名称')}</td>
					<td class="w120">{t('数据表名')}</td>
					<td>{t('说明')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag" title="{t('拖动排序')}" data-placement="right">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
					<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
					<td class="vt center none"><img src="{ZOTOP_URL_APPS}/{a('form.dir')}/app.png" alt="{$r['name']}"></td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							<a href="{u('form/table/index/'.$r['id'])}">{t('数据管理')}</a>
							<s></s>
							<a href="{u('form/fields/index/'.$r['id'])}">{t('字段管理')}</a>
							<s></s>
							<a href="{u('form/admin/edit/'.$r['id'])}">{t('表单设置')}</a>
							<s></s>

							{if $r['disabled']}
							<a href="{u('form/admin/status/'.$r['id'])}" class="dialog-confirm">{t('启用')}</a>
							{else}
							<a href="{u('form/admin/status/'.$r['id'])}" class="dialog-confirm">{t('禁用')}</a>
							{/if}

							<s></s>
							<a href="{u('form/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{$r.table}</td>
					<td>{$r.description}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{/if}
	</div>
	<div class="main-footer">
	{a('form.description')}
	</div>
</div>
{template 'footer.php'}